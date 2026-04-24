<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ClassRoom;
use App\Models\Evaluation;

class EvaluationSeeder extends Seeder
{
    public function run(): void
    {
        $classrooms = ClassRoom::with('disciplines')->get();

        foreach ($classrooms as $classroom) {

            foreach ($classroom->disciplines as $discipline) {

                // 🔥 quantidade de unidades da turma
                $units = $classroom->units ?? 3;

                for ($unit = 1; $unit <= $units; $unit++) {

                    // 🔥 padrão: 3 avaliações somando 10
                    $evaluations = [
                        ['name' => 'Prova', 'value' => 5],
                        ['name' => 'Trabalho', 'value' => 3],
                        ['name' => 'Atividade', 'value' => 2],
                    ];

                    foreach ($evaluations as $ev) {

                        Evaluation::create([
                            'classroom_id' => $classroom->id,
                            'discipline_id' => $discipline->id,
                            'unit' => $unit,
                            'name' => $ev['name'],
                            'description' => null,
                            'date' => now(),
                            'value' => $ev['value']
                        ]);
                    }
                }
            }
        }
    }
}