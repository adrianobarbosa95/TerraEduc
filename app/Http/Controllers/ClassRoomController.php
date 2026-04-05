<?php

namespace App\Http\Controllers;

use App\Models\ClassRoom;
use Illuminate\Http\Request;
use Termwind\Components\Dd;

class ClassRoomController extends Controller
{
   public function index()
    {
        $classrooms = ClassRoom::all();
        return view('classrooms.index', compact('classrooms'));
    }

    public function create()
    {
        return view('classrooms.create');
    }
public function store(Request $request)
{
    $request->validate([
        'modality' => 'required',
        'year' => 'required|integer'
    ]);

    $units = 2;

    if (in_array($request->modality, ['PROEI', 'INTEGRADO'])) {
        $units = 3;
    }

    $name = $request->name;

    ClassRoom::create([
        'name' => $name,
        'modality' => $request->modality,
        'year' => $request->year,
        'period' => $request->period,
        'units' => $units
    ]);

    return response()->json(['success' => true]);
}

    /**
     * Display the specified resource.
     */
    public function show(ClassRoom $classRoom)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ClassRoom $classRoom)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
   public function update(Request $request, $id)
{
    $classroom = ClassRoom::findOrFail($id);

    $classroom->update([
        'year' => $request->year,
        'name' => $request->name,
        'modality' => $request->modality,
        
        'period' => $request->period,
        'units' => $request->units,
    ]);

    return response()->json(['success' => "sucesso"]);
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ClassRoom $classRoom)
{
  
    
$classRoom->delete();

   
    
     

    return response()->json(['sucess' => $classRoom->id]);
}
}
