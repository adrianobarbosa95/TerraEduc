<?php

namespace App\Http\Controllers;

use App\Models\ClassDisciplineSchedule;
use Illuminate\Support\Facades\Auth;

class ScheduleController extends Controller
{
   public function index()
{
    $user = Auth::user();

    $schedules = ClassDisciplineSchedule::with([
        'discipline',
        'classroom'
    ])
    ->whereHas('discipline', function ($q) use ($user) {
        $q->where('user_id', $user->id);
    })
    ->get();

    $days = [
        1 => 'Segunda',
        2 => 'Terça',
        3 => 'Quarta',
        4 => 'Quinta',
        5 => 'Sexta',
    ];

    $timeSlots = [
        'M' => [
            1 => '07:40 - 08:30',
            2 => '08:30 - 09:20',
            3 => '09:20 - 10:10',
            4 => '10:10 - 11:00',
            5 => '11:00 - 11:50',
            6 => '11:50 - 12:40',
        ],
        'T' => [
            1 => '13:10 - 14:00',
            2 => '14:00 - 14:50',
            3 => '14:50 - 15:40',
            4 => '15:40 - 16:30',
            5 => '16:30 - 17:20',
            6 => '17:20 - 18:10',
        ],
        'N' => [
            1 => '18:30 - 19:20',
            2 => '19:20 - 20:10',
            3 => '20:10 - 21:00',
            4 => '21:00 - 21:50',
            5 => '21:50 - 22:40',
        ],
    ];

    $grid = [];

    foreach ($schedules as $schedule) {

        // 🔥 CORRETO: quebra por caractere
        $slots = str_split($schedule->slots);

        foreach ($slots as $slot) {

            $slot = (int) $slot;

            $grid[$schedule->shift][$schedule->day][$slot][] = $schedule;
        }
    }

    return view('schedules.index', compact('grid', 'days', 'timeSlots'));
}
}