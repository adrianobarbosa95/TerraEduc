<?php

namespace App\Http\Controllers;

use App\Models\User;

class ProfessorController extends Controller
{
     public function show($slug)
    {
        $professor = User::where('slug', $slug)->firstOrFail();

        return view('professores.show', compact('professor'));
    }
}
