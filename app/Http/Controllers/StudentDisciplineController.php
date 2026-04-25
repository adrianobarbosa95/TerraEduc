<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Discipline;
use App\Models\Plan;

class StudentDisciplineController extends Controller
{
    public function plan($id)
    {
        $discipline = Discipline::with([
            'user',
            'classrooms.schedules'
        ])->findOrFail($id);

        $classroom = $discipline->classrooms->first();

        // pega os dias da semana da disciplina (campo correto: day)
        $weekDays = $classroom->schedules
            ->where('discipline_id', $discipline->id)
            ->pluck('day')
            ->toArray();

        // fallback caso não tenha horário cadastrado
        if (empty($weekDays)) {
            $weekDays = [1, 2, 3, 4, 5];
        }
 

Carbon::setLocale('pt_BR');
        $start = Carbon::create(null, 2, 1);   // 01 fevereiro
        $end   = Carbon::create(null, 12, 24); // 24 dezembro

        $classes = [];
        $count = 1;

        while ($start <= $end) {

            if (in_array($start->dayOfWeek, $weekDays)) {

                $classes[] = [
    'number' => $count++,

    // DATA PARA O BANCO
    'date'   => $start->format('Y-m-d'),

    // DATA BONITA PARA EXIBIR
    'formatted' => $start->format('d/m/Y'),

    // DIA DA SEMANA
    'day'    => ucfirst($start->translatedFormat('l'))
];
            }

            $start->addDay();
        }
        $plans = Plan::where('discipline_id', $discipline->id)
    ->where('classroom_id', $classroom->id)
    ->get()
    ->keyBy('date');
 
        return view('students.plan', compact(
            'discipline',
            'classroom',
            'classes', 
             'plans'
        ));
    }
}