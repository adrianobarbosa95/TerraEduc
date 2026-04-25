<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    protected $fillable = [
        'discipline_id',
        'classroom_id',
        'date',
        'content',
        'slide',
        'activity'
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