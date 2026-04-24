<?php

namespace App\Http\Controllers;

use App\Models\Discipline;
use App\Models\Grade;
use Illuminate\Http\Request;
use App\Models\ClassRoom;

use App\Models\Student;
use App\Models\Evaluation;
use Illuminate\Support\Facades\Auth;

class GradeController extends Controller
{
    public function index()
    {
        $disciplines = Discipline::all();
        return view('disciplines.index', compact('disciplines'));
    }
public function store(Request $request)
{
    if (!$request->grades) {
        return back()->with('error', 'Nenhuma nota enviada.');
    }

    foreach ($request->grades as $studentId => $evaluations) {

        foreach ($evaluations as $evaluationId => $value) {

            // 🔥 vazio vira 0
            $value = ($value !== null && $value !== '') ? $value : 0;

            \App\Models\Grade::updateOrCreate(
                [
                    'student_id' => $studentId,
                    'evaluation_id' => $evaluationId
                ],
                [
                    'value' => $value
                ]
            );
        }
    }

    return redirect()->back()->with('success', 'Notas salvas com sucesso!');
}
public function getDisciplines($id)
{
     $classroom = ClassRoom::findOrFail($id);

    $disciplines = $classroom->disciplines()
        ->where('user_id', Auth::id()) // 🔥 FILTRO AQUI
        ->get();

    return response()->json($disciplines);
}
    /**
     * Display a listing of the resource.
     */
    

public function getData(Request $request)
{
    $classroomId = $request->classroom_id;
    $disciplineId = $request->discipline_id;
    $unit = $request->unit;

    // 🔒 VALIDAR SE A DISCIPLINA É DO PROFESSOR LOGADO
    $discipline = \App\Models\Discipline::where('id', $disciplineId)
        ->where('user_id', Auth::id())
        ->first();

    if (!$discipline) {
        return response()->json([
            'error' => 'Disciplina inválida.'
        ], 403);
    }

    // 👇 alunos
    $students = Student::where('classroom_id', $classroomId)->get();

    // 👇 avaliações (agora seguro)
    $evaluations = Evaluation::where([
        'classroom_id' => $classroomId,
        'discipline_id' => $disciplineId,
        'unit' => $unit
    ])->get();

    // 👇 notas
    $grades = Grade::whereIn('evaluation_id', $evaluations->pluck('id'))
        ->whereIn('student_id', $students->pluck('id'))
        ->get();

    return response()->json([
        'students' => $students,
        'evaluations' => $evaluations,
        'grades' => $grades
    ]);
}

    /**
     * Show the form for creating a new resource.
     */
     

    /**
     * Store a newly created resource in storage.
     */
     public function create()
{
    $classrooms = ClassRoom::all();

    return view('grades.create', compact('classrooms'));
}

    /**
     * Display the specified resource.
     */
    public function show(Grade $grade)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Grade $grade)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Grade $grade)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Grade $grade)
    {
        //
    }
//   public function data(Request $request)
// {
//     $classroomId = $request->classroom_id;
//     $disciplineId = $request->discipline_id;
//     $unit = $request->unit;

//     // 🔥 alunos da turma
//     $students = \App\Models\Student::where('classroom_id', $classroomId)->get();

//     // 🔥 avaliações da turma/disciplina/unidade
//     $evaluations = \App\Models\Evaluation::where([
//         'classroom_id' => $classroomId,
//         'discipline_id' => $disciplineId,
//         'unit' => $unit
//     ])->get();

//     // 🔥 NOTAS (AQUI ESTAVA O ERRO PROVAVEL)
//     $grades = \App\Models\Grade::whereIn('evaluation_id', $evaluations->pluck('id'))
//         ->whereIn('student_id', $students->pluck('id'))
//         ->get();
// dd($grades);
//     return response()->json([
//         'students' => $students,
//         'evaluations' => $evaluations,
//         'grades' => $grades
//     ]);
// }
}
