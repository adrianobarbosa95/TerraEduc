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
        return $this->belongsToMany(Discipline::class, 'class_discipline');
    }
}
