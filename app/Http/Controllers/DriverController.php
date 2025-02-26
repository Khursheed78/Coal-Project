<?php

namespace App\Http\Controllers;

use App\Models\Driver;
use App\Models\Vehicle;
use Illuminate\Http\Request;

class DriverController extends Controller
{
    public function create()
    {

        // $customers = Customer::all();
        // $suppliers = Supplier::all();
        $drivers = Driver::all();
        return view('driver.driver', compact('drivers'));
    }
    public function DriverStore(Request $request)
    {


        // Store Driver data
        $request->validate([
               'name'           => 'required|string|max:255',
               'vehicle_number' => 'required|string|max:255',
               'phone'          => 'required|numeric|digits_between:7,15',
               'number_of_trips'=> 'required|numeric',
               'balance'        => 'required|numeric',
           ]);
        $driver = Driver::create([
            'name'               => $request->name,
            'vehicle_number'     => $request->vehicle_number,
            'phone'              => $request->phone,
            'number_of_trips'    => $request->number_of_trips,
            'balance'            => $request->balance,

        ]);
        return response()->json([
            'success' => true,
            'message' => 'Driver added successfully!',
            'data'    => $driver
        ]);
    }
    public function UpdateDriver(Request $request, $id)
    {
        $request->validate([
            'name'           => 'required|string|max:255',
            'vehicle_number' => 'required|string|max:255',
            'phone'          => 'required|numeric|digits_between:7,15',
            'number_of_trips' => 'required|numeric',
            'balance'        => 'required|numeric',
        ]);

        $driver = Driver::find($id);

        if (!$driver) {
            return response()->json([
                'success' => false,
                'message' => 'Driver not found!',
            ], 404);
        }
        $driver->update([
            'name'           => $request->name,
            'vehicle_number' => $request->vehicle_number,
            'phone'          => $request->phone,
            'number_of_trips' => $request->number_of_trips,
            'balance'        => $request->balance,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Driver updated successfully!',
            'data'    => $driver
        ]);
    }

    public function DeleteDriver($id)
    {
        $driver = Driver::find($id);
        if ($driver) {
            $driver->delete();
            return response()->json(['success' => 'Driver deleted successfully']);
        } else {
            return response()->json(['error' => 'Driver not found'], 404);
        }
    }
    public function updateBalance(Request $request)
{
    $driver = Driver::find($request->driver_id);

    if (!$driver) {
        return response()->json(['error' => 'Driver not found'], 404);
    }

    // Deduct payment amount from balance
    $driver->balance -= $request->payment_amount;
    if ($driver->balance <= 0) {
        $driver->balance = 0;  // Ensure balance is zero
        $driver->number_of_trips = 0;     // Reset trips to zero
    }

    $driver->save();

    return response()->json([
        'success' => true,
        'new_balance' => $driver->balance,
        'new_trips' => $driver->number_of_trips
    ]);
}

//     public function updateBalance(Request $request)
// {
//     $driver = Driver::find($request->driver_id);

//     if (!$driver) {
//         return response()->json(['error' => 'Driver not found'], 404);
//     }

//     // Deduct payment amount from balance
//     $driver->balance -= $request->payment_amount;
//     if ($driver->balance < 0) {
//         $driver->balance = 0; // Ensure balance never goes negative
//     }

//     $driver->save();

//     return response()->json([
//         'success' => true,
//         'new_balance' => $driver->balance
//     ]);
// }
}
