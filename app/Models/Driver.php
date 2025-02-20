<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Driver extends Model
{

    protected $fillable = [
        'name',
        'vehicle_number',
        'phone',
        'number_of_trips',
        'balance',

    ];
}
