<?php

namespace App\Http\Controllers;

use App\Models\ClassRoom;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
// use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\IOFactory;

class StudentController extends Controller
{
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

    public function create()
    {
        $classrooms = ClassRoom::all();
        return view('students.create', compact('classrooms'));
    }

    public function store(Request $request)
    {
        
// pega o primeiro nome
 
$firstName = Str::ascii(strtolower(explode(' ', trim($request->name))[0] ?? ''));
        Student::create([
            'name' => $request->name,
            'registration' => $request->registration,
            'password' => bcrypt($request->firstName.$request->registration),
            'classroom_id' => $request->classroom_id,
        ]);

        return redirect()->route('students.index');
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

        foreach ($rows as $row) {

            $cols = $row->getElementsByTagName('td');

            if ($cols->length < 3) continue;

            $registration = trim($cols->item(1)->nodeValue);
            $name = trim($cols->item(2)->nodeValue);

            // ignora cabeçalho
            if ($registration == 'Matrícula') continue;

           
 
// pega o primeiro nome
$firstName = Str::ascii(strtolower(explode(' ', trim($name))[0] ?? '')); 
Student::create([
    'name' => $name,
    'registration' => $registration,
    'password' => bcrypt($firstName.$registration), // 👈 aqui
    'classroom_id' => $request->classroom_id
]);
        }

        return back()->with('success', 'Alunos importados!');

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
