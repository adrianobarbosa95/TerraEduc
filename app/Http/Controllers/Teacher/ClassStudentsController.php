<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClassStudentsController extends Controller
{
    /**
     * Mostra a lista completa de alunos de uma turma que foram aprovados ou estão em recuperação.
     *
     * Observações:
     * - Este código assume que há tabelas: students, enrollments, grades, classes.
     * - grades tem colunas: student_id, class_id, unit (int), grade (decimal)
     * - enrollments tem: student_id, class_id
     * - classes tem: id, modality (opcional)
     *
     * Ajuste nomes de tabelas/colunas conforme seu projeto.
     */
    public function index(Request $request, $classId)
    {
        // tenta determinar a quantidade de unidades (2 ou 3)
        $units = $request->input('units');

        $class = DB::table('classes')->where('id', $classId)->first();

        if (!$units) {
            if ($class && isset($class->modality)) {
                // Exemplo: ajuste as modalidades conforme sua base
                $units = in_array($class->modality, ['modalidade_com_2_unidades','two_units']) ? 2 : 3;
            } else {
                $units = 3; // valor padrão
            }
        }

        $approvedThreshold = 7.0;
        $recoveryThreshold = 4.0;

        // calcula média do aluno na turma (considera todas as notas registradas)
        $students = DB::table('students as s')
            ->join('enrollments as e', 's.id', '=', 'e.student_id')
            ->leftJoin('grades as g', function ($join) use ($classId) {
                $join->on('g.student_id', '=', 's.id')->where('g.class_id', $classId);
            })
            ->where('e.class_id', $classId)
            ->select('s.id', 's.name as student_name', DB::raw('ROUND(AVG(g.grade),2) as average'))
            ->groupBy('s.id', 's.name')
            ->get()
            ->map(function ($row) use ($approvedThreshold, $recoveryThreshold) {
                $avg = $row->average !== null ? (float) $row->average : 0.0;
                $status = $avg >= $approvedThreshold ? 'Aprovado' : ($avg >= $recoveryThreshold ? 'Recuperação' : 'Reprovado');
                return (object) [
                    'id' => $row->id,
                    'name' => $row->student_name,
                    'average' => $avg,
                    'status' => $status,
                ];
            })
            ->filter(function ($row) {
                return in_array($row->status, ['Aprovado', 'Recuperação']);
            })
            ->values();

        return view('teacher.class_students', [
            'class' => $class,
            'students' => $students,
            'units' => $units,
            'approvedThreshold' => $approvedThreshold,
            'recoveryThreshold' => $recoveryThreshold,
        ]);
    }
}
