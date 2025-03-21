<?php

namespace App\Http\Controllers;

use App\Models\Stock;
use App\Models\Driver;
use App\Models\Customer;
use App\Models\Supplier;
use Illuminate\Http\Request;
use App\Models\PurchaseInvoice;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Http\Controllers\Controller;
use App\Models\Purchase;

class PurchaseController extends Controller
{
    public function stock()
    {
        $stocks = Purchase::all();
        return view('stock.stock', compact('stocks'));
    }
    public function newpurchase()
    {
        $customers = Customer::all();
        $suppliers = Supplier::all();
        $drivers = Driver::all();
        $purchases = Purchase::with(['supplier', 'driver'])
            ->get()
            ->map(function ($purchase) {
                $purchase->total_amount = number_format(
                    ($purchase->quantity * $purchase->price_per_ton) +
                    $purchase->transportation_cost +
                    $purchase->supplier_balance +
                    $purchase->driver_balance, 2
                );
                return $purchase;
            });

        $totaltrips = Purchase::count();
        $supplierCount = Purchase::distinct('supplier_id')->count('supplier_id');
        $driverCount = Purchase::distinct('driver_id')->count('driver_id');

        // Count occurrences of each supplier and store their names
        $supplierTrips = Purchase::with('supplier')
            ->get()
            ->groupBy('supplier_id')
            ->map(function ($items) {
                return [
                    'name' => $items->first()->supplier->supplier_name ?? 'N/A',
                    'count' => $items->count()
                ];
            });

        // Count occurrences of each driver and store their names
        $driverTrips = Purchase::with('driver')
            ->get()
            ->groupBy('driver_id')
            ->map(function ($items) {
                return [
                    'name' => $items->first()->driver->name ?? 'N/A',
                    'count' => $items->count()
                ];
            });

        return view('Purchases.NewPurchase', compact(
            'customers',
            'suppliers',
            'drivers',
            'purchases',
            'supplierCount',
            'driverCount',
            'totaltrips',
            'supplierTrips',
            'driverTrips'
        ));
    }


    public function store(Request $request)
    {
        $request->merge([
            'from' => $request->from_place,
            'to' => $request->to_place,
        ]);

        // Validate request
        $data =  $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'driver_id' => 'required|exists:drivers,id',
            'quantity' => 'required|numeric|min:1',
            'price_per_ton' => 'required|numeric|min:1',
            'supplier_balance' => 'nullable|numeric|min:0',
            'driver_balance' => 'nullable|numeric|min:0',
            'transportation_cost' => 'nullable|numeric|min:0',
            'from' => 'required|string|max:255',
            'to' => 'required|string|max:255',
            'date' => 'required|date'
        ]);

        // Default values
        $quantity = $request->quantity;
        $pricePerTon = $request->price_per_ton;
        $transportationCost = $request->transportation_cost;

        // Calculate total price including transportation cost
        $totalPrice = ($quantity * $pricePerTon) + $transportationCost;

        $purchase = Purchase::create([
            'supplier_id' => $request->supplier_id,
            'driver_id' => $request->driver_id,
            'quantity' => $quantity,
            'price_per_ton' => $pricePerTon,
            'total_price' => $totalPrice,
            'supplier_balance' => $request->supplier_balance,
            'driver_balance' => $request->driver_balance,
            'transportation_cost' => $transportationCost,
            'from' => $request->from,
            'to' => $request->to,
            'date' => $request->date,
        ]);


        return response()->json([
            'success' => 'Purchase added successfully!',
            'purchase' => $purchase
        ]);
    }

    // Payment Section
    public function processPayment(Request $request)
    {
        $request->validate([
            'id' => 'required|integer', // Purchase ID
            'field' => 'required|in:supplier_balance,driver_balance',
            'amount' => 'required|numeric|min:0'
        ]);

        // Find Purchase entry
        $purchase = Purchase::findOrFail($request->id);

        // Update balance
        $newBalance = $purchase->{$request->field} - $request->amount;
        $purchase->update([$request->field => $newBalance]);

        return response()->json([
            'success' => true,
            'new_balance' => $newBalance
        ]);
    }



    // Generate Pdf Section
    public function generatePDF(Request $request)
    {
        // Fetch the purchase record based on the purchase ID
        $purchase = Purchase::with(['supplier', 'driver'])->findOrFail($request->purchase_id);

        $data = [
            'date' => $purchase->date,
            'supplier' => $purchase->supplier,
            'driver' => $purchase->driver,
            'supplier_name' => $purchase->supplier->supplier_name ?? 'N/A',
            'supplier_phone' => $purchase->supplier->phone ?? 'N/A',
            'driver_name' => $purchase->driver->name ?? 'N/A',
            'quantity_tons' => $purchase->quantity,
            'supplier_balance' => $purchase->supplier_balance,
            'driver_balance' => $purchase->driver_balance,
            'price_per_ton' => $purchase->price_per_ton,
            'transportation_cost' => $purchase->transportation_cost,
            'total_price' => ($purchase->quantity * $purchase->price_per_ton) + $purchase->transportation_cost,
            'from' => $purchase->from,
            'to' => $purchase->to,
        ];

        // Generate PDF from Blade view
        $pdf = Pdf::loadView('Invoices.Purchasepdf', $data);

        return $pdf->download('purchase_invoice_' . $purchase->id . '.pdf');
    }

    // Delete Section
    public function deletePurchase(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:purchases,id'
        ]);

        $purchase = Purchase::findOrFail($request->id);
        $purchase->delete(); // Delete the record

        return response()->json(['success' => true, 'message' => 'Purchase deleted successfully']);
    }

    //Update Section
    public function UpdatePurchase(Request $request, $id)
    {
        // Validate incoming request
        $validatedData = $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'driver_id' => 'required|exists:drivers,id',
            'quantity' => 'required|numeric',
            'price_per_ton' => 'required|numeric',
            'transportation_cost' => 'nullable|numeric',
            'from' => 'required|string|max:255',
            'to' => 'required|string|max:255',
            'supplier_balance' => 'nullable|numeric',
            'driver_balance' => 'nullable|numeric',
        ]);

        // Find the existing purchase record
        $purchase = Purchase::findOrFail($id);

        // Update the fields
        $purchase->update($validatedData);

        // Return JSON response for AJAX
        return response()->json([
            'success' => true,
            'message' => 'Supplier details updated successfully!',
            'data' => [
                'id' => $purchase->id,
                'supplier_name' => $purchase->supplier->supplier_name ?? 'N/A',
                'driver_name' => $purchase->driver->name ?? 'N/A',
                'quantity' => $purchase->quantity,
                'price_per_ton' => $purchase->price_per_ton,
                'from' => $purchase->from,
                'to' => $purchase->to,
                'transportation_cost' => $purchase->transportation_cost,
                'supplier_balance' => $purchase->supplier_balance,
                'driver_balance' => $purchase->driver_balance,
            ]
        ]);
    }




}
