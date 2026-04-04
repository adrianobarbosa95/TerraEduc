<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Grade extends Model
{
    protected $fillable = [
        'name',
        'evaluations_per_unit'
    ];

    public function classrooms()
    {
        return $this->belongsToMany(ClassRoom::class, 'class_discipline');
    }

    public function grades()
    {
        return $this->hasMany(Grade::class);
    }
}
