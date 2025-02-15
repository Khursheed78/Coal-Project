<?php

namespace App\Http\Controllers;

use App\Models\Subjects;
use Illuminate\Http\Request;

class Classes extends Controller
{
    protected $table = 'classes';

    protected $fillable = [
        'name', 'class_code', 'section', 'total_students',
        'teacher_id', 'class_timing', 'status'
    ];
    // public function subjects() {
    //     return $this->hasMany(Subjects::class, 'class_id');
    // }

}
