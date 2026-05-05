<?php

namespace App\Http\Controllers;

use App\Models\ClassRoom;
use App\Models\Student;
use App\Models\StudentClassroomHistory;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
 
 

class StudentController extends Controller
{
   
public function bulkDelete(Request $request)
{
    Student::whereIn('id', $request->ids)->delete();

    return response()->json(['success' => true]);
}
public function changeClassroom(Request $request)
{
    $request->validate([
        'student_id' => 'required|exists:students,id',
        'classroom_id' => 'required|exists:classrooms,id',
    ]);

    $student = Student::findOrFail($request->student_id);

    // 🔍 pega histórico atual (sem saída)
    $current = StudentClassroomHistory::where('student_id', $student->id)
        ->whereNull('left_at')
        ->first();

    // ❌ se já estiver na mesma turma
    if ($current && $current->classroom_id == $request->classroom_id) {
        return back()->with('error', 'Aluno já está nessa turma.');
    }

    // 🔒 fecha histórico atual
    if ($current) {
        $current->update([
            'left_at' => Carbon::now()
        ]);
    }

    // 🆕 cria novo histórico
    StudentClassroomHistory::create([
        'student_id' => $student->id,
        'classroom_id' => $request->classroom_id,
        'year' => date('Y'),
        'entered_at' => Carbon::now(),
        'left_at' => null
    ]);

    // 🔄 atualiza turma atual do aluno
    $student->update([
        'classroom_id' => $request->classroom_id
    ]);

    return back()->with('success', 'Aluno movido com sucesso!');
}
   public function index(Request $request)
{
    $classrooms = ClassRoom::all();

    $query = Student::with('classroom');

    // 🔎 filtro por turma
    if ($request->classroom_id) {
        $query->where('classroom_id', $request->classroom_id);
    }

    // 🔎 busca por nome ou matrícula
    if ($request->search) {
        $query->where(function ($q) use ($request) {
            $q->where('name', 'like', "%{$request->search}%")
              ->orWhere('registration', 'like', "%{$request->search}%");
        });
    }

    // 🔥 ESSENCIAL (evita travar)
    $students = $query->paginate(10);

    return view('students.index', compact('students', 'classrooms'));
}
 

public function history($id)
{
    $student = Student::with('history.classroom')->findOrFail($id);

    return view('students.history', compact('student'));
}
    public function create()
    {
        $classrooms = ClassRoom::all();
        return view('students.create', compact('classrooms'));
    }

    
public function store(Request $request)
{
    $firstName = Str::ascii(strtolower(explode(' ', trim($request->name))[0] ?? ''));

    $student = Student::create([
        'name' => $request->name,
        'registration' => $request->registration,
        'password' => bcrypt($firstName . $request->registration),
        'classroom_id' => $request->classroom_id,
    ]);

    // 🔥 histórico inicial
    StudentClassroomHistory::create([
        'student_id' => $student->id,
        'classroom_id' => $request->classroom_id,
        'year' => now()->year, // melhor que date()
        'entered_at' => now(),
    ]);

    return redirect()->route('students.index')
        ->with('success', 'Aluno cadastrado com histórico!');
}

    

public function import(Request $request)
{
    try {

        $file = $request->file('file');
        $html = file_get_contents($file->getPathname());

        libxml_use_internal_errors(true);

        $dom = new \DOMDocument();
        $dom->loadHTML($html);

        $rows = $dom->getElementsByTagName('tr');

        $created = 0;
        $updated = 0;

        foreach ($rows as $row) {

            $cols = $row->getElementsByTagName('td');

            if ($cols->length < 3) continue;

            $registration = trim($cols->item(1)->nodeValue);
            $name = trim($cols->item(2)->nodeValue);

            if ($registration == 'Matrícula') continue;

            $student = Student::where('registration', $registration)->first();

            // 🔁 SE JÁ EXISTE → ATUALIZA
            if ($student) {

                if ($student->classroom_id != $request->classroom_id) {

                    // fecha histórico atual
                    $current = StudentClassroomHistory::where('student_id', $student->id)
                        ->whereNull('left_at')
                        ->first();

                    if ($current) {
                        $current->update([
                            'left_at' => now()
                        ]);
                    }

                    // novo histórico
                    StudentClassroomHistory::create([
                        'student_id' => $student->id,
                        'classroom_id' => $request->classroom_id,
                        'year' => now()->year,
                        'entered_at' => now(),
                    ]);

                    $student->update([
                        'classroom_id' => $request->classroom_id
                    ]);

                    $updated++;
                }

                continue;
            }

            // 🆕 NOVO ALUNO
            $firstName = Str::ascii(strtolower(explode(' ', trim($name))[0] ?? ''));

            $student = Student::create([
                'name' => $name,
                'registration' => $registration,
                'password' => bcrypt($firstName . $registration),
                'classroom_id' => $request->classroom_id
            ]);

            StudentClassroomHistory::create([
                'student_id' => $student->id,
                'classroom_id' => $request->classroom_id,
                'year' => now()->year,
                'entered_at' => now(),
            ]);

            $created++;
        }

        return back()->with('success',
            "Importação concluída! Novos: $created | Atualizados: $updated"
        );

    } catch (\Exception $e) {
        return back()->with('error', $e->getMessage());
    }
}
    

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
   // ================= UPDATE (AJAX) =================
    public function update(Request $request, $id)
    {
        $student = Student::findOrFail($id);
 
 $firstName = Str::ascii(strtolower(explode(' ', trim($request->name))[0] ?? ''));
        $student->update([
            'name' => $request->name,
            'registration' => $request->registration,
            'password' =>  bcrypt($firstName.$request->registration),
        ]);

        return response()->json(['success' => true]);
    }

    // ================= DELETE (AJAX) =================
    public function destroy($id)
    {
        $student = Student::findOrFail($id);
        $student->delete();

        return response()->json(['success' => true]);
    }
}
