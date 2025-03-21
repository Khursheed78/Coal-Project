<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DriverDetails extends Model
{
    use HasFactory;

    protected $fillable = [
        'driver_id',
        'quantity',
        'price_per_ton',
        'total_price',
        'transportation_cost',
        'driver_balance',
        'from',
        'to',
        'date'
    ];

    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }
}
