<?php

namespace Database\Seeders;

use App\Models\ClassRoom;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
class ClassroomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
{
    ClassRoom::create([
        'name' => '1º Ano Informática',
        'modality' => 'INTEGRADO',
        'year' => 2026,
        'period' => 1,
        'units' => 3
    ]);

    ClassRoom::create([
        'name' => '2º Ano Informática',
        'modality' => 'INTEGRADO',
        'year' => 2026,
        'period' => 1,
        'units' => 3
    ]);
}
}
