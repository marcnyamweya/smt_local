<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Student extends Model
{
    use HasFactory;

    protected $fillable = ['teacher_id', 'name', 'email', 'age','phone','course'];

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }
}

