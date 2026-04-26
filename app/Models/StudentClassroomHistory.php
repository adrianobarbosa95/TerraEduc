<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentClassroomHistory extends Model
{
    protected $fillable = [
        'student_id',
        'classroom_id',
        'year',
        'entered_at',
        'left_at'
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function classroom()
    {
        return $this->belongsTo(ClassRoom::class);
    }
}