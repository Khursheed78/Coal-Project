<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Classes extends Model
{
    // use HasFactory;


    protected $fillable = ['class_name', 'class_code','section','total_students','class_timing','status'];


    public function teachers()
{
    return $this->belongsToMany(Teachers::class, 'teacher_class');
}
}
