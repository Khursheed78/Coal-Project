<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Driver extends Model
{

    use HasFactory;

    protected $fillable = ['name', 'vehicle_number', 'phone', 'no_of_trips', 'balance'];

    public function driverDetails()
    {
        return $this->hasMany(DriverDetails::class, 'driver_id');
    }
}
