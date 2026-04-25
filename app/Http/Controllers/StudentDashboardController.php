<?php

namespace App\Http\Controllers;
 
use Illuminate\Support\Facades\Auth;

class StudentDashboardController extends Controller
{
     

    public function index()
{
    $student = Auth::guard('students')->user();

    if (!$student) {
        return redirect('/aluno/login');
    }
    
    $student->load([
        'classroom.disciplines.user',
        'grades.evaluation',
        'grades.discipline'
    ]);


    // 📊 AGRUPAR NOTAS POR DISCIPLINA
    $labels = [];
    $values = [];

    foreach ($student->grades->groupBy('discipline.name') as $discipline => $grades) {
        $labels[] = $discipline;
        $values[] = $grades->avg('value');
    }

    return view('students.dashboard', compact(
        'student',
        'labels',
        'values'
    ));
}
}