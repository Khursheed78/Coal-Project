<?php

namespace App\Http\Controllers;


use App\Models\Stock;
use App\Models\User;
use App\Models\Driver;
use App\Models\Parents;
use App\Models\Customer;
use App\Models\Purchase;
use App\Models\Subjects;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Container\Attributes\Auth;

class AdminController extends Controller
{

    public function dashboard(){
        $suppliers = Supplier::all();
        $customers = Customer::all();
        $driver = Driver::all();
        $totalStock = Purchase::sum('quantity');

        return view('admin.dashboard',compact('suppliers','customers','driver','totalStock'));
    }


}


