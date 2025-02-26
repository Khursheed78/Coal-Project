<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PurchaseInvoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'supplier_id',
        'driver_id',
        'date',
        'quantity_tons',
        'average_price_per_ton',
        'total_balance'
    ];

    public function supplier() {
        return $this->belongsTo(Supplier::class);
    }

    public function driver() {
        return $this->belongsTo(Driver::class);
    }
}
