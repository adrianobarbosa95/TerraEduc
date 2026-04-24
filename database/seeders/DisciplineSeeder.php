<?php

namespace Database\Seeders;

use App\Models\ClassRoom;
use App\Models\Discipline;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DisciplineSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // 🔥 cria disciplinas
        $math = Discipline::create(['name' => 'Matemática', 'user_id' => 1]);
        $port = Discipline::create(['name' => 'Português', 'user_id' => 1]);
        $prog = Discipline::create(['name' => 'Programação', 'user_id' => 1]);

        // 🔥 pega todas as turmas
        $classrooms = ClassRoom::all();

        // 🔥 associa TODAS disciplinas a TODAS turmas
        foreach ($classrooms as $classroom) {
            $classroom->disciplines()->syncWithoutDetaching([
                $math->id,
                $port->id,
                $prog->id
            ]);
        }
    }
}
