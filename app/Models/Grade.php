<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Grade extends Model
{
    protected $fillable = [
        'student_id',
        'evaluation_id',
        'name',
        'value',
        'evaluations_per_unit'
    ];
 public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function evaluation()
    {
        return $this->belongsTo(Evaluation::class);
    }
    public function classrooms()
    {
        return $this->belongsToMany(ClassRoom::class, 'class_discipline');
    }

    public function grades()
    {
        return $this->hasMany(Grade::class);
    }
}
