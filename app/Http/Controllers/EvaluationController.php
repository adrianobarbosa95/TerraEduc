<?php
namespace App\Http\Controllers;

use App\Models\Evaluation;
use App\Models\ClassRoom;
use App\Models\Discipline;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
class EvaluationController extends Controller
{
    public function index()
    {
         $classrooms = ClassRoom::all();
        $disciplines = Discipline::all();
        $evaluations = Evaluation::with(['classroom', 'discipline'])->get();

        return view('evaluations.index', compact('evaluations', 'classrooms', 'disciplines'));
    }

    public function create()
    {
        $classrooms = ClassRoom::all();
       $disciplines = Discipline::where('user_id', Auth::id())->get();

        return view('evaluations.index', compact('classrooms', 'disciplines'));
    }

    public function store(Request $request)
{
    $request->validate([
        'classroom_id' => 'required|exists:classrooms,id',
        'discipline_id' => 'required|exists:disciplines,id',
        'unit' => 'required|integer|min:1',
        'evaluations' => 'required|array|min:3',
    ]);
$discipline = Discipline::where('id', $request->discipline_id)
    ->where('user_id', Auth::id())
    ->first();

if (!$discipline) {
    return back()->with('error', 'Disciplina inválida.');
}
    $evaluations = $request->evaluations;

    $total = 0;

    foreach ($evaluations as $index => $evaluation) {

        if (empty($evaluation['name']) || !isset($evaluation['value'])) {
            return back()->with('error', "Preencha todos os campos da avaliação #" . ($index + 1));
        }

        if (!is_numeric($evaluation['value']) || $evaluation['value'] <= 0) {
            return back()->with('error', "Valor inválido na avaliação #" . ($index + 1));
        }

        $total += floatval($evaluation['value']);
    }

    // 🔥 REGRAS
    if (count($evaluations) < 3) {
        return back()->with('error', 'É obrigatório no mínimo 3 avaliações.');
    }

    if ($total != 10) {
        return back()->with('error', "A soma deve ser exatamente 10. Atual: $total");
    }

    // 🚫 BLOQUEIO DE DUPLICIDADE
    $exists = \App\Models\Evaluation::where([
        'classroom_id' => $request->classroom_id,
        'discipline_id' => $request->discipline_id,
        'unit' => $request->unit
    ])->exists();

    if ($exists) {
        return back()->with('error', 'Já existe avaliação cadastrada para essa unidade.');
    }

    // 🔥 TRANSAÇÃO (ESSENCIAL)
    DB::beginTransaction();

    try {

        foreach ($evaluations as $evaluation) {

            \App\Models\Evaluation::create([
                'classroom_id' => $request->classroom_id,
                'discipline_id' => $request->discipline_id,
                'unit' => $request->unit,
                'name' => $evaluation['name'],
                'description' => $evaluation['description'] ?? null,
                'date' => $evaluation['date'] ?? null,
                'value' => $evaluation['value']
            ]);
        }

        DB::commit();

        return redirect()->route('evaluations.index')
            ->with('success', 'Avaliações cadastradas com sucesso!');

    } catch (\Exception $e) {

        DB::rollBack();

        return back()->with('error', 'Erro ao salvar avaliações.');
    }
}
    public function destroy(Evaluation $evaluation)
    {
        $evaluation->delete();

        return back()->with('success', 'Avaliação removida!');
    }

    public function getClassroomData($id)
{
    $classroom = \App\Models\ClassRoom::with('disciplines')->findOrFail($id);
$disciplines = $classroom->disciplines()
        ->where('user_id', Auth::id()) // 🔥 FILTRO AQUI
        ->get();
        if (!$disciplines) {
    return back()->with('error', 'Disciplina inválida.');
}
    return response()->json([
        'disciplines' => $disciplines,
        'units' => $classroom->units // 2 ou 3
    ]);
}
}