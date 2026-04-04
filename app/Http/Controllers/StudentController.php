<?php

namespace App\Http\Controllers;

use App\Models\ClassRoom;
use App\Models\Student;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function index()
    {
        $students = Student::with('classroom')->get();
        return view('students.index', compact('students'));
    }

    public function create()
    {
        $classrooms = ClassRoom::all();
        return view('students.create', compact('classrooms'));
    }

    public function store(Request $request)
    {
        Student::create([
            'name' => $request->name,
            'registration' => $request->registration,
            'password' => bcrypt($request->password),
            'classroom_id' => $request->classroom_id,
        ]);

        return redirect()->route('students.index');
    }
    /**
     * Display a listing of the resource.
     */
    

    /**
     * Show the form for creating a new resource.
     */
     

    /**
     * Store a newly created resource in storage.
     */
    

    /**
     * Display the specified resource.
     */
    public function show(Student $student)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Student $student)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Student $student)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Student $student)
    {
        //
    }
}
