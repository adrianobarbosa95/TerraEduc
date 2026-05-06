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

public function index() { 
    $user = Auth::user(); $schedules = ClassDisciplineSchedule::with([ 'discipline', 'classroom' ]) ->whereHas('discipline', function ($q) use ($user) { $q->where('user_id', $user->id); }) ->get(); $days = [ 2 => 'Segunda', 3 => 'Terça', 4 => 'Quarta', 5 => 'Quinta', 6 => 'Sexta', ]; $timeSlots = [ 'M' => [ 1 => '07:40 - 08:30', 2 => '08:30 - 09:20', 3 => '09:20 - 10:10', 4 => '10:20 - 11:10', 5 => '11:10 - 12:00', 6 => '12:00 - 12:50', ], 'T' => [ 1 => '13:10 - 14:00', 2 => '14:00 - 14:50', 3 => '14:50 - 15:40', 4 => '15:40 - 16:30', 5 => '16:30 - 17:20', 6 => '17:20 - 18:10', ], 'N' => [ 1 => '18:30 - 19:20', 2 => '19:20 - 20:10', 3 => '20:10 - 21:00', 4 => '21:00 - 21:50', 5 => '21:50 - 22:40', ], ]; $grid = []; foreach ($schedules as $schedule) { // 🔥 CORRETO: quebra por caractere
     $slots = str_split($schedule->slots); foreach ($slots as $slot) { 
        $slot = (int) $slot; $grid[$schedule->shift][$schedule->day][$slot][] = $schedule; } } 
        return view('schedules.index', compact('grid', 'days', 'timeSlots')); } 
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