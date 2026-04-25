<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Student;
use Illuminate\Support\Facades\Hash;

class StudentAuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.student-login');
    }

   public function login(Request $request)
{
    $request->validate([
        'registration' => 'required',
        'password' => 'required'
    ]);

    $student = Student::where('registration', $request->registration)->first();

    if (!$student) {
        return back()->withErrors([
            'registration' => 'Matrícula inválida'
        ]);
    }

    

  if (!Hash::check($request->password, $student->password)) {
    return back()->withErrors([
        'password' => 'Senha inválida'
    ]);
}
    Auth::guard('students')->login($student);

    return redirect('/aluno/dashboard');
}

   public function logout(Request $request)
{
    Auth::guard('students')->logout();

    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect()->route('welcome');
}
}