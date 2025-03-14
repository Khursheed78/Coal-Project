<?php

namespace App\Models;

use App\Models\Driver;
use App\Models\Supplier;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Purchase extends Model
{
    use HasFactory;

    protected $fillable = ['supplier_id', 'driver_id', 'quantity', 'price_per_ton', 'from', 'to','transportation_cost','supplier_balance','driver_balance','date'];


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
