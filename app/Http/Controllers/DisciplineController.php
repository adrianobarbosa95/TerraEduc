<?php

namespace App\Http\Controllers;

use App\Models\ClassDisciplineSchedule;
use App\Models\Discipline;
use App\Models\ClassRoom;
use App\Models\EvaluationRule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DisciplineController extends Controller
{
    /**
     * LISTAR
     */
    public function index()
    {
        $disciplines = Discipline::with(['evaluationRules.classroom'])
            ->where('user_id', Auth::id())
            ->get();

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
            'name' => $request->name,
            'user_id' => Auth::id()
        ]);

        $classroomIds = $request->classrooms ?? [];

        // 🔥 RELACIONAMENTO DISCIPLINA x TURMAS
        if (!empty($classroomIds)) {
            $discipline->classrooms()->sync($classroomIds);
        }

        // 🔥 REGRAS DE AVALIAÇÃO
        if ($request->rules) {

            foreach ($request->rules as $classroomId => $units) {

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

        // 🔥 HORÁRIOS POR TURMA (CORRIGIDO)
        foreach ($classroomIds as $classroomId) {

            $text = $request->schedules[$classroomId] ?? '';

            $lines = explode("\n", $text);

            foreach ($lines as $schedule) {

                $schedule = trim($schedule);

                if (!$schedule) continue;

                if (!preg_match('/(\d)([MTN])(\d+)/', $schedule, $parts)) {
                    continue;
                }

                ClassDisciplineSchedule::create([
                    'classroom_id' => $classroomId,
                    'discipline_id' => $discipline->id,
                    'day' => $parts[1],
                    'shift' => $parts[2],
                    'slots' => $parts[3]
                ]);
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

        $discipline->update([
            'name' => $request->name
        ]);

        // 🔥 REMOVE REGRAS ANTIGAS
        EvaluationRule::where('discipline_id', $discipline->id)->delete();

        // 🔥 REMOVE HORÁRIOS ANTIGOS
        ClassDisciplineSchedule::where('discipline_id', $discipline->id)->delete();

        // 🔥 ATUALIZA TURMAS
        $classroomIds = $request->classrooms ?? [];
        $discipline->classrooms()->sync($classroomIds);

        // 🔥 REGRAS NOVAS
        if ($request->rules) {

            foreach ($request->rules as $classroomId => $units) {

                if (!in_array($classroomId, $classroomIds)) continue;

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

        // 🔥 HORÁRIOS NOVOS
        foreach ($classroomIds as $classroomId) {

            $text = $request->schedules[$classroomId] ?? '';

            $lines = explode("\n", $text);

            foreach ($lines as $schedule) {

                $schedule = trim($schedule);

                if (!$schedule) continue;

                if (!preg_match('/(\d)([MTN])(\d+)/', $schedule, $parts)) {
                    continue;
                }

                ClassDisciplineSchedule::create([
                    'classroom_id' => $classroomId,
                    'discipline_id' => $discipline->id,
                    'day' => $parts[1],
                    'shift' => $parts[2],
                    'slots' => $parts[3]
                ]);
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

        EvaluationRule::where('discipline_id', $id)->delete();
        ClassDisciplineSchedule::where('discipline_id', $id)->delete();

        $discipline->delete();

        return response()->json(['success' => true]);
    }
}