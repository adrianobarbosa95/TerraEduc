<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
 protected $fillable = [
        'name',
        'registration',
        'password',
        'classroom_id'
    ];

    public function classroom()
    {
        return $this->belongsTo(ClassRoom::class);
    }

    public function grades()
    {
        return $this->hasMany(Grade::class);
    }
}
