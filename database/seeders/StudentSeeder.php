<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Student;
use App\Models\ClassRoom;

class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $classroom = ClassRoom::first();

    for ($i = 1; $i <= 10; $i++) {
        Student::create([
            'name' => "Aluno $i",
            'registration' => "2026$i",
            'classroom_id' => $classroom->id,
            'password' => 1234
        ]);
    }
    }
}
