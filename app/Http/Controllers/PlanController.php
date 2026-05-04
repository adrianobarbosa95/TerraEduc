<?php

namespace App\Http\Controllers;

use App\Models\ClassDisciplineSchedule;
use App\Models\Plan;
use App\Models\Discipline;
use App\Models\ClassRoom;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PlanController extends Controller
{

    // 📌 VISÃO PROFESSOR (EDITAR)
    public function teacher($disciplineId, $classroomId)
    {
        $discipline = Discipline::with('user')->findOrFail($disciplineId);
        $classroom = ClassRoom::with('schedules')->findOrFail($classroomId);


        $schedules = ClassDisciplineSchedule::where([
            'classroom_id' => $classroom->id,
            'discipline_id' => $discipline->id
        ])->get();
        Carbon::setLocale('pt_BR');

        // 📅 GERAR AULAS (FEV → DEZ)
        $start = Carbon::create(null, 2, 1);
        $end   = Carbon::create(null, 12, 24);

        $classes = [];
        $i = 1;

        while ($start <= $end) {

            foreach ($schedules  as $schedule) {

                if ($start->dayOfWeekIso == $schedule->day-1) {

                    $classes[] = [
                        'number' => $i++,
                        'date' => $start->format('Y-m-d'),
                        'formatted' => $start->format('d/m/Y'),
                        'day' => $start->translatedFormat('l')
                    ];
                }
            }

            $start->addDay();
        }

        // 📦 PEGAR PLANOS SALVOS
        $plans = Plan::where('discipline_id', $discipline->id)
            ->where('classroom_id', $classroom->id)
            ->get()
            ->keyBy('date');

        return view('plans.index', compact(
            'discipline',
            'classroom',
            'classes',
            'plans',
            'schedules'
        ));
    }

    // 💾 SALVAR
    public function store(Request $request)
    {
        foreach ($request->plans as $date => $data) {

            Plan::updateOrCreate(
                [
                    'discipline_id' => $request->discipline_id,
                    'classroom_id' => $request->classroom_id,
                    'date' => $date,
                ],
                [
                    'content' => $data['content'] ?? null,
                    'slide' => $data['slide'] ?? null,
                    'activity' => $data['activity'] ?? null,
                ]
            );
        }

        return back()->with('success', 'Plano salvo com sucesso');
    }
}
