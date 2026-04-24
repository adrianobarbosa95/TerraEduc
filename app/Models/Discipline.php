<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Discipline extends Model
{
    protected $fillable = [
        'name',
        'user_id'
    ];


    public function classrooms()
    {
        return $this->belongsToMany(
            ClassRoom::class,
            'class_discipline',
            'discipline_id',
            'classroom_id'
        ); //->withPivot('evaluations_per_unit') 👈 ESSENCIAL
    }

    public function grades()
    {
        return $this->hasMany(Grade::class);
    }
    public function evaluationRules()
    {
        return $this->hasMany(EvaluationRule::class);
    }
   public function user()
{
    return $this->belongsTo(User::class);
}
    public function schedules()
    {
        return $this->hasMany(ClassDisciplineSchedule::class);
    }
}
