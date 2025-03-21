<?php

namespace App\Http\Controllers;

use App\Models\Driver;
use App\Models\Vehicle;
use App\Models\Customer;
use App\Models\Purchase;
use App\Models\Supplier;

use Illuminate\Http\Request;
use App\Models\DriverDetails;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class DriverController extends Controller
{
    public function create()
    {

        // $customers = Customer::all();
        // $suppliers = Supplier::all();
        $drivers = Driver::latest()->paginate(7);
        return view('driver.driver', compact('drivers'));
    }
    public function DriverStore(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'vehicle_number' => 'required|string|max:255',
            'phone' => 'required|numeric|digits_between:7,15',
            'no_of_trips' => 'required|integer|min:0',
            'balance' => 'required|numeric|min:0',
        ]);

        $driver = Driver::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Driver added successfully!',
            'driver' => $driver // Correct key name to return data
        ]);
    }



    public function UpdateDriver(Request $request, $id)
    {
        // ✅ Validate request data
        $request->validate([
            'name' => 'required|string|max:255',
            'vehicle_number' => 'required|string|max:255',
            'phone' => 'required|numeric|digits_between:7,15',
            'no_of_trips' => 'required|numeric', // Corrected field name
            'balance' => 'required|numeric',
        ]);

        // ✅ Find driver or return error if not found
        $driver = Driver::findOrFail($id);

        // ✅ Update driver record
        $driver->update([
            'name' => $request->name,
            'vehicle_number' => $request->vehicle_number,
            'phone' => $request->phone,
            'no_of_trips' => $request->no_of_trips, // Corrected field name
            'balance' => $request->balance,
        ]);

        // ✅ Return success response
        return response()->json([
            'success' => true,
            'message' => 'Driver updated successfully!',
            'data' => $driver
        ], 200);
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

    //     public function updateBalance(Request $request)
// {
//     $driver = Driver::find($request->driver_id);

    //     if (!$driver) {
//         return response()->json(['error' => 'Driver not found'], 404);
//     }

    //     // Deduct payment amount from balance
//     $driver->balance -= $request->payment_amount;
//     if ($driver->balance <= 0) {
//         $driver->balance = 0;  // Ensure balance is zero
//         $driver->number_of_trips = 0;     // Reset trips to zero
//     }

    //     $driver->save();

    //     return response()->json([
//         'success' => true,
//         'new_balance' => $driver->balance,
//         'new_trips' => $driver->number_of_trips
//     ]);
// }

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

    // Driver Details
    public function DriverDetailsCreate()
    {
        $allDrivers = Driver::all();

        $drivers = DriverDetails::with('driver')
            ->latest()
            ->paginate(7)
            ->through(function ($detail) {
                if ($detail->driver) {
                    // Manually fetch the correct balance
                    $driver = Driver::find($detail->driver->id);
                    // Log::info("Driver ID: {$detail->driver->id}, Previous Balance: {$driver->balance}, New Balance: {$detail->driver_balance}");
                    // Correct balance calculation
                    $previousBalance = $driver->balance ?? 0;
                    $newBalance = $detail->driver_balance ?? 0;

                    $detail->total_balance = $previousBalance + $newBalance;
                    $detail->total_trips = ($driver->no_of_trips ?? 0) + ($driver->driverDetails->count() ?? 0);
                } else {
                    $detail->total_balance = $detail->driver_balance ?? 0;
                    $detail->total_trips = 0;
                }

                return $detail;
            });

        return view('driver.driverdetails', compact('drivers', 'allDrivers'));
    }



    public function storeDriverDetails(Request $request)
    {
        // ✅ Validate Request Data
        $request->validate([
            'driver_id' => 'required|exists:drivers,id',
            'quantity' => 'required|numeric|min:0',
            'price_per_ton' => 'required|numeric|min:0',
            'transportation_cost' => 'required|numeric|min:0',
            'driver_balance' => 'required|numeric|min:0',
            'from' => 'required|string|max:255',
            'to' => 'required|string|max:255',
            'date' => 'required|date',
        ]);

        // ✅ Calculate Total Price
        $total_price = ($request->quantity * $request->price_per_ton) + $request->transportation_cost;

        // ✅ Store Data in Database
        $driverDetail = DriverDetails::create([
            'driver_id' => $request->driver_id,
            'quantity' => $request->quantity,
            'price_per_ton' => $request->price_per_ton,
            'total_price' => $total_price, // Auto-calculated
            'transportation_cost' => $request->transportation_cost,
            'driver_balance' => $request->driver_balance,
            'from' => $request->from,
            'to' => $request->to,
            'date' => $request->date,
        ]);

        // ✅ Return JSON Response
        return response()->json([
            'success' => true,
            'message' => 'Driver details stored successfully!',
            'data' => $driverDetail
        ], 201);
    }

    // Payment Section
    public function processPayment(Request $request)
    {
        $request->validate([
            'id' => 'required|integer', // Driver ID
            'field' => 'required|in:driver_balance', // Ensure it's for drivers
            'amount' => 'required|numeric|min:0'
        ]);

        // Find DriverDetails entry
        $driverDetails = DriverDetails::findOrFail($request->id);

        // Ensure the balance is not going negative
        if ($request->amount > $driverDetails->{$request->field}) {
            return response()->json([
                'success' => false,
                'message' => 'Payment amount exceeds current balance!'
            ], 400);
        }

        // Update balance
        $newBalance = $driverDetails->{$request->field} - $request->amount;
        $driverDetails->update([$request->field => $newBalance]);

        return response()->json([
            'success' => true,
            'new_balance' => $newBalance
        ]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:driver_details,id',
            'quantity' => 'required|numeric',
            'price_per_ton' => 'required|numeric',
            'transportation_cost' => 'required|numeric',
            'driver_balance' => 'nullable|numeric',
            'from' => 'required|string',
            'to' => 'required|string',
            'date' => 'required|date',
        ]);

        $driverDetail = DriverDetails::find($request->id);
        $driverDetail->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Driver details updated successfully!',
            'data' => $driverDetail
        ]);
    }
    // ✅ Delete Driver Function
    public function destroy(Request $request)
    {
        try {
            $id = $request->id;
            $driver = DriverDetails::find($id);

            // Check if driver exists
            if (!$driver) {
                return response()->json(['success' => false, 'message' => 'Driver not found.'], 404);
            }

            $driver->delete();

            return response()->json(['success' => true, 'message' => 'Driver deleted successfully.']);
        } catch (\Exception $e) {

            return response()->json(['success' => false, 'message' => 'Error deleting driver.'], 500);
        }
    }
    public function generatePDF($id)
    {
        $driverDetail = DriverDetails::findOrFail($id);

        // Fetch the related driver
        $driver = $driverDetail->driver;

        // Get total trips (Driver's recorded trips + details trips)
        $totalTrips = $driver->no_of_trips + DriverDetails::where('driver_id', $driver->id)->count();

        // Fetch previous balance from the 'drivers' table
        $previousBalance = $driver->balance;

        // Calculate total balance (Previous Balance + Current Driver Balance)
        $totalBalance = $previousBalance + $driverDetail->driver_balance;

        // Determine the Current Balance status
        $currentBalance = $driverDetail->driver_balance == 0 ? 'Paid' : 'Unpaid';

        // Generate the PDF
        $pdf = PDF::loadView('Driver.DriverPdf', compact('driverDetail', 'driver', 'totalTrips', 'previousBalance', 'totalBalance', 'currentBalance'));

        return $pdf->download('driver_invoice_' . $driver->name . '.pdf');
    }

    public function searchByPhone(Request $request)
    {
        $phone = $request->phone;
        $drivers = DriverDetails::with('driver')->whereHas('driver', function ($query) use ($phone) {
            $query->where('phone', 'LIKE', "%$phone%");
        })->get();

        return response()->json(['success' => true, 'drivers' => $drivers]);

    }
}



