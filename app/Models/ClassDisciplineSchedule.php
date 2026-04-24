<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClassDisciplineSchedule extends Model
{
    protected $fillable = [
        'classroom_id',
        'discipline_id',
        'day',
        'shift',
        'slots'
    ];
  public function discipline()
{
    return $this->belongsTo(Discipline::class);
}

public function classroom()
{
    return $this->belongsTo(ClassRoom::class);
}
 
}
