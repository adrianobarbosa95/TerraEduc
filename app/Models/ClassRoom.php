<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClassRoom extends Model
{
    protected $fillable = [
        'name',
        'units'
    ];

    public function students()
    {
        return $this->hasMany(Student::class);
    }

    public function disciplines()
    {
        return $this->belongsToMany(Discipline::class, 'class_discipline');
    }
}
