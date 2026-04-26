<?php
namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\StudentClassroomHistory;
use Illuminate\Http\Request;

class StudentMovementController extends Controller
{
    public function promoteClassroom(Request $request)
{
    $students = Student::where('classroom_id', $request->from_classroom)->get();

    foreach ($students as $student) {

        StudentClassroomHistory::create([
            'student_id' => $student->id,
            'classroom_id' => $student->classroom_id,
            'year' => date('Y'),
            'entered_at' => now()->subYear(),
            'left_at' => now(),
        ]);

        $student->update([
            'classroom_id' => $request->to_classroom
        ]);
    }

    return back()->with('success', 'Turma promovida com sucesso!');
}
    // 🔁 MUDANÇA DE TURMA (mesmo ano)
    public function changeClassroom(Request $request, Student $student)
    {
        $oldClassroom = $student->classroom_id;

        // salva histórico
        StudentClassroomHistory::create([
            'student_id' => $student->id,
            'classroom_id' => $oldClassroom,
            'year' => date('Y'),
            'entered_at' => now()->subMonths(6), // opcional
            'left_at' => now(),
        ]);

        // atualiza turma atual
        $student->update([
            'classroom_id' => $request->classroom_id
        ]);

        return back()->with('success', 'Aluno transferido com sucesso!');
    }

    // ⬆️ PROMOÇÃO (novo ano)
    public function promote(Request $request, Student $student)
    {
        $oldClassroom = $student->classroom_id;

        // salva histórico do ano anterior
        StudentClassroomHistory::create([
            'student_id' => $student->id,
            'classroom_id' => $oldClassroom,
            'year' => date('Y'),
            'entered_at' => now()->subYear(),
            'left_at' => now(),
        ]);

        // nova turma
        $student->update([
            'classroom_id' => $request->classroom_id
        ]);

        return back()->with('success', 'Aluno promovido!');
    }
}