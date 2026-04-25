<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClassRoom extends Model
{
    protected $table = 'classrooms';
    protected $fillable = [
        'name',
        'units',
        'modality',
        'year',
        'period'
    ];


    public function students()
    {
        return $this->hasMany(Student::class, 'classroom_id'); // 👈 aqui
    }


    public function disciplines()
    {
        return $this->belongsToMany(
            Discipline::class,
            'class_discipline',
            'classroom_id',
            'discipline_id'
        ); //->withPivot('evaluations_per_unit') 👈 ESSENCIAL
    }
    public function evaluationRules()
    {
        return $this->hasMany(EvaluationRule::class);
    }
   public function schedules()
{
    return $this->hasMany(
        ClassDisciplineSchedule::class,
        'classroom_id' // 👈 nome correto no banco
    );
}
}
