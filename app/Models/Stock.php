<?php

namespace App\Models;

use App\Models\Driver;
use App\Models\Supplier;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Stock extends Model
{
    use HasFactory;

    protected $fillable = [
        'supplier_id', 'supplier_name', 'supplier_phone', 'supplier_balance',
        'driver_id', 'driver_name', 'trips', 'balance', 'quantity', 'price_per_ton',
    ];

    // Relationship with Supplier (Assuming you have a Supplier model)
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    // Relationship with Driver (Assuming you have a Driver model)
    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }
}
