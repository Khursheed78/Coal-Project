<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $fillable = [
        'customer_id',
        'supplier_id',
        'invoice_number',
        'invoice_date',
        'total_amount',
        'amount_paid',
        'balance_due',
        'payment_status',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }
    // public function invoice()
    // {
    //     return $this->belongsTo(Invoice::class, 'invoice_id');
    // }


}
