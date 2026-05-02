<?php

use App\Http\Controllers\EvaluationController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\StudentAuthController;
use App\Http\Controllers\ClassRoomController;
use App\Http\Controllers\DisciplineController;
use App\Http\Controllers\GradeController;
use App\Http\Controllers\PlanController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\StudentDashboardController;
use App\Http\Controllers\StudentDisciplineController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StudentMovementController;


    Route::get('/', function () {
    return view('welcome');
})->name("welcome");
Route::get('/tutorial', function () {
    return view('tutorial.index');
})->name('tutorial'); 
Route::get('/dashboard', function () {
    return redirect()->route("home");
})->middleware(['auth', 'verified'])->name('dashboard');
Route::get('/aluno/login', [StudentAuthController::class, 'showLogin'])->name("login-student");
Route::post('/aluno/login', [StudentAuthController::class, 'login']);
Route::post('/aluno/logout', [StudentAuthController::class, 'logout'])->name("logout-student");

Route::middleware('auth:students')->group(function () {
    Route::get('/aluno/dashboard', [StudentDashboardController::class, 'index'])
        ->name('student.dashboard');
    Route::get('/aluno/disciplina/{id}/plano', [StudentDisciplineController::class, 'plan'])
    ->name('student.discipline.plan');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
    Route::get('/home', function () {return view('home.index');})->name('home');
    Route::get('/grades/data', [App\Http\Controllers\GradeController::class, 'getData']);
    Route::get('/disciplina/data', [App\Http\Controllers\GradeController::class, 'getData']);
    Route::get('/schedule', [ScheduleController::class, 'index'])
        ->name('schedules.index')
        ->middleware('auth');
    // PROFESSOR
    Route::get('/professor/plan/{discipline}/{classroom}', [PlanController::class, 'teacher'])
        ->name('plans.teacher');
    // SALVAR
    Route::post('/plans/store', [PlanController::class, 'store'])
        ->name('plans.store');
    Route::post('/check-password', function (\Illuminate\Http\Request $request) {
        $user = Auth::user();
        return response()->json([
            'valid' => Hash::check($request->password, $user->password)
        ]);
    })->name('check.password');

    Route::get('/classrooms/{id}/disciplines', [GradeController::class, 'getDisciplines']);
    Route::get('/classrooms/{id}/data', [EvaluationController::class, 'getClassroomData']);
    Route::resource('evaluations', EvaluationController::class);
    Route::resource('students', StudentController::class);
    Route::delete('classrooms/{classRoom}', [ClassRoomController::class, 'destroy']);
    Route::put('classrooms/{classRoom}', [ClassRoomController::class, 'update']);
    Route::resource('classrooms', ClassRoomController::class);
    Route::resource('disciplines', DisciplineController::class);
    Route::resource('grades', GradeController::class);
    Route::post('/students/import', [StudentController::class, 'import'])->name('students.import');
Route::post('/students/{student}/change-classroom', [StudentMovementController::class, 'changeClassroom']);
Route::post('/students/{student}/promote', [StudentMovementController::class, 'promote']);
Route::get('/students/{id}/history', [StudentController::class, 'history'])
    ->name('students.history');
    Route::post('/classrooms/promote', [StudentMovementController::class, 'promoteClassroom']);
Route::post('/students/change-classroom', [StudentController::class, 'changeClassroom'])
    ->name('students.changeClassroom');
Route::post('/students/bulk-delete', [StudentController::class, 'bulkDelete'])
    ->name('students.bulkDelete');
    });


require __DIR__ . '/auth.php';
