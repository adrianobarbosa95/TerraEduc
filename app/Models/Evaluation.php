<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Evaluation extends Model
{
    protected $fillable = [
        'classroom_id',
        'discipline_id',
        'unit',
        'name',
        'description',
        'date',
        'value'
    ];

    public function classroom()
    {
        return $this->belongsTo(ClassRoom::class);
    }

    public function discipline()
    {
        return $this->belongsTo(Discipline::class);
    }
}