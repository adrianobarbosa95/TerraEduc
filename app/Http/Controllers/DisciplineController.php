<?php

namespace App\Http\Controllers;

use App\Models\Discipline;
use App\Models\ClassRoom;
use App\Models\EvaluationRule;
use Illuminate\Http\Request;

class DisciplineController extends Controller
{
    /**
     * LISTAR
     */
    public function index()
    {
        $disciplines = Discipline::with(['evaluationRules.classroom'])->get();

        return view('disciplines.index', compact('disciplines'));
    }

    /**
     * FORM CREATE
     */
    public function create()
    {
        $classrooms = ClassRoom::all();

        return view('disciplines.create', compact('classrooms'));
    }

    /**
     * SALVAR
     */
 public function store(Request $request)
{
    $request->validate([
        'name' => 'required',
        'classrooms' => 'nullable|array'
    ]);

    $discipline = Discipline::create([
        'name' => $request->name
    ]);

    $classroomIds = $request->classrooms ?? [];

    // 🔥 SALVA RELACIONAMENTO CORRETO
    if (!empty($classroomIds)) {
        $discipline->classrooms()->sync($classroomIds);
    }

    // 🔥 SALVA REGRAS SOMENTE DAS MARCADAS
    if ($request->rules) {

        foreach ($request->rules as $classroomId => $units) {

            // 👇 IGNORA TURMAS NÃO MARCADAS
            if (!in_array($classroomId, $classroomIds)) {
                continue;
            }

            foreach ($units as $unit => $quantity) {

                if (!$quantity) continue;

                EvaluationRule::create([
                    'classroom_id' => $classroomId,
                    'discipline_id' => $discipline->id,
                    'unit' => $unit,
                    'quantity' => $quantity
                ]);
            }
        }
    }

    return redirect()->route('disciplines.index')
        ->with('success', 'Disciplina criada com sucesso!');
}
    /**
     * EDIT
     */
    public function edit($id)
    {
        $discipline = Discipline::with('evaluationRules')->findOrFail($id);
        $classrooms = ClassRoom::all();

        return view('disciplines.edit', compact('discipline', 'classrooms'));
    }

    /**
     * UPDATE
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required'
        ]);

        $discipline = Discipline::findOrFail($id);

        // atualiza nome
        $discipline->update([
            'name' => $request->name
        ]);

        // remove regras antigas
        EvaluationRule::where('discipline_id', $discipline->id)->delete();

        // recria regras
        if ($request->rules) {

            foreach ($request->rules as $classroomId => $units) {

                foreach ($units as $unit => $quantity) {

                    if (!$quantity) continue;

                    EvaluationRule::create([
                        'classroom_id' => $classroomId,
                        'discipline_id' => $discipline->id,
                        'unit' => $unit,
                        'quantity' => $quantity
                    ]);
                }
            }
        }

        return redirect()->route('disciplines.index')
            ->with('success', 'Disciplina atualizada!');
    }

    /**
     * DELETE
     */
    public function destroy($id)
    {
        $discipline = Discipline::findOrFail($id);

        // remove regras primeiro
        EvaluationRule::where('discipline_id', $id)->delete();

        $discipline->delete();

        return response()->json(['success' => true]);
    }
}  