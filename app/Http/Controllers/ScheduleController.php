<?php

namespace App\Http\Controllers;

use App\Models\Discipline;
use App\Models\ClassRoom;
use App\Models\Plan;
use App\Models\ClassDisciplineSchedule;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class ScheduleController extends Controller
{
    public function plan($disciplineId, $classroomId)
    {
         $student = Auth::guard('students')->user();

    if (!$student) {
        return redirect('/aluno/login');
    }
        // 🔥 disciplina
        $discipline = Discipline::with('user')->findOrFail($disciplineId);

        // 🔥 turma correta (NUNCA usar first())
        $classroom = ClassRoom::findOrFail($classroomId);

        // 🔥 schedules isolado por disciplina + turma
        $schedules = ClassDisciplineSchedule::where([
            'classroom_id' => $classroom->id,
            'discipline_id' => $discipline->id
        ])->get();

        // 🔥 plans CORRIGIDO (isso aqui estava vazando outra turma)
        $plans = Plan::where([
            'classroom_id' => $classroom->id,
            'discipline_id' => $discipline->id
        ])->get()->keyBy('date');

        Carbon::setLocale('pt_BR');

        $start = Carbon::create(null, 2, 1);
        $end   = Carbon::create(null, 12, 24);

        $classes = [];
        $i = 1;

        while ($start <= $end) {

            $day = (int) $start->isoFormat('E');

            // ignora sábado e domingo
            if ($day > 5) {
                $start->addDay();
                continue;
            }

            // só gera aula se estiver no schedule
            if ($schedules->pluck('day')->contains($day)) {

                $classes[] = [
                    'number' => $i++,
                    'date' => $start->format('Y-m-d'),
                    'formatted' => $start->format('d/m/Y'),
                    'day' => ucfirst($start->translatedFormat('l'))
                ];
            }

            $start->addDay();
        }

        return view('students.plan', compact(
            'discipline',
            'classroom',
            'classes',
            'plans',
            'schedules'
        ));
    }
}