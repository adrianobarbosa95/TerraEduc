<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Student extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'name',
        'registration',
        'password',
        'classroom_id'
    ];

    protected $hidden = [
        'password',
        'remember_token'
    ];

    // 🏫 relacionamento com turma
    public function classroom()
    {
        return $this->belongsTo(ClassRoom::class);
    }
public function history()
{
    return $this->hasMany(\App\Models\StudentClassroomHistory::class);
}
    // 📊 notas
    public function grades()
    {
        return $this->hasMany(Grade::class);
    }
}