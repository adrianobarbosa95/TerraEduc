<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Carbon\CarbonPeriod;
use App\Models\Discipline;
use App\Models\ClassRoom;
use App\Models\Plan;
use App\Models\ClassDisciplineSchedule;
use Illuminate\Support\Facades\Auth;

class StudentDisciplineController extends Controller
{
    public function plan($id)
    {
        $student = Auth::guard('students')->user();

        if (!$student) {
            return redirect('/aluno/login');
        }

        // 🔥 disciplina vem da URL
        $discipline = Discipline::with('user')->findOrFail($id);

        // 🔥 turma vem do aluno (NUNCA do first())
        $classroom = $student->classroom()
            ->whereHas('disciplines', function ($q) use ($id) {
                $q->where('disciplines.id', $id);
            })
            ->firstOrFail();

        // 🔥 schedules corretos
        $schedules = ClassDisciplineSchedule::where([
            'classroom_id' => $classroom->id,
            'discipline_id' => $discipline->id
        ])->get();

        $weekDays = $schedules->pluck('day')->toArray();

        if (empty($weekDays)) {
            $weekDays = [2, 3, 4, 5, 6];
        }

        Carbon::setLocale('pt_BR');

        $period = CarbonPeriod::create(
            Carbon::create(null, 2, 1),
            Carbon::create(null, 12, 24)
        );

        $classes = [];
        $count = 1;

        foreach ($period as $date) {

            // 🔥 ISO correto: 1 = segunda ... 7 = domingo
            $day = (int) $date->isoFormat('E')+1;

            if ($day > 5) {
                continue;
            }

            if (in_array($day, $weekDays)) {

                $classes[] = [
                    'number' => $count++,
                    'date' => $date->format('Y-m-d'),
                    'formatted' => $date->format('d/m/Y'),
                    'day' => ucfirst($date->translatedFormat('l'))
                ];
            }
        }

        // 🔥 plans isolado corretamente
        $plans = Plan::where([
            'discipline_id' => $discipline->id,
            'classroom_id' => $classroom->id
        ])->get()->keyBy('date');

        return view('students.plan', compact(
            'discipline',
            'classroom',
            'classes',
            'plans'
        ));
    }
}