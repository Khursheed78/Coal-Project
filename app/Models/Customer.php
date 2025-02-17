<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $fillable = [
        'customer',
        'contact_person',
        'phone',
        'email',
        'balance',
        'address',
    ];
}
