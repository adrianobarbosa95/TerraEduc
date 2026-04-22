<?php

use App\Http\Controllers\EvaluationController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\ClassRoomController;
use App\Http\Controllers\DisciplineController;
use App\Http\Controllers\GradeController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\StudentController;

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');
        Route::get('/home', function () {
    return view('home.index');
})->name('home');
Route::get('/classrooms/{id}/data', [EvaluationController::class, 'getClassroomData']);
Route::resource('evaluations', EvaluationController::class);
Route::resource('students', StudentController::class);
Route::delete('classrooms/{classRoom}', [ClassRoomController::class, 'destroy']);
Route::put('classrooms/{classRoom}', [ClassRoomController::class, 'update']);
Route::resource('classrooms', ClassRoomController::class);
Route::resource('disciplines', DisciplineController::class);
Route::resource('grades', GradeController::class);
Route::post('/students/import', [StudentController::class, 'import'])
    ->name('students.import');

  
});


require __DIR__.'/auth.php';
