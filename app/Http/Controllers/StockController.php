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

class StockController extends Controller
{
    public function stock(){
        $stocks = Stock::all();
        return view ('stock.stock',compact('stocks'));
    }
    public function newpurchase() {
        $customers = Customer::all();
        $suppliers = Supplier::all();
        $invoices = PurchaseInvoice::all();
        $drivers = Driver::all();

        return view('Invoices.NewPurchase',compact('invoices','customers','suppliers','drivers'));
    }
    public function store(Request $request)
    {
        // Validate request
        $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'driver_id' => 'required|exists:drivers,id',
            'quantity' => 'required|numeric|min:1',
            'price_per_ton' => 'required|numeric|min:1',
            'total_price' => 'required|numeric|min:1' // Match AJAX field
        ]);

        // Calculate total price
        $total_price = $request->quantity * $request->price_per_ton;

        // Find supplier and driver
        $supplier = Supplier::findOrFail($request->supplier_id);
        $driver = Driver::findOrFail($request->driver_id);

        // Create stock record
        $stock = Stock::create([
            'supplier_id' => $supplier->id,
            'supplier_name' => $supplier->supplier_name,
            'supplier_phone' => $supplier->phone,
            'supplier_balance' => $supplier->balance,
            'driver_id' => $driver->id,
            'driver_name' => $driver->name,
            'trips' => $driver->number_of_trips,
            'balance' => $driver->balance,
            'quantity' => $request->quantity,
            'price_per_ton' => $request->price_per_ton,
            'total_price' => $total_price // Store correctly
        ]);

        return response()->json([
            'success' => 'Stock record added successfully!',
            'stock' => $stock
        ]);
    }



    public function generatePDF(Request $request)
    {
        $supplier = Supplier::find($request->supplier_id);
         $driver = Driver::find($request->driver_id);
        // Get input values from the form request
        $data = [
            'supplier' => $supplier,
             'driver' => $driver,
            'supplier_name' => $request->supplier_name,
            'supplier_phone' => $request->supplier_phone,
            'supplier_balance' => $request->supplier_balance,
            'driver_name' => $request->driver_name,
            'driver_trips' => $request->driver_trips,
            'driver_balance' => $request->driver_balance,
            'quantity_tons' => $request->quantity,
            'price_per_ton' => $request->price_per_ton,
            'total_price' => $request->quantity * $request->price_per_ton
        ];

        // Generate PDF from Blade view
        $pdf = Pdf::loadView('Invoices.Purchasepdf', $data);

        // Return the generated PDF as a download
        return $pdf->download('purchase_invoice.pdf');
    }


        // public function generatePDF(Request $request)
        // {
        //      // Fetch supplier and driver details based on selected IDs
        //      $supplier = Supplier::find($request->supplier_id);
        //      $driver = Driver::find($request->driver_id);

        //      // Data for the PDF
        //      $data = [
        //          'supplier' => $supplier,
        //          'driver' => $driver,
        //          'quantity_tons' => $request->quantity,
        //          'price_per_ton' => $request->price_per_ton,
        //          'total_price' => $request->totalprice,
        //      ];

        //      // Load PDF view and pass data
        //      $pdf = PDF::loadView('Invoices.Purchasepdf', $data);

        //      // Download the generated PDF
        //      return $pdf->download('purchase_invoice.pdf');
        //  }

    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'supplier_id' => 'required|exists:suppliers,id',
    //         'supplier_name' => 'required|string',
    //         'supplier_phone' => 'required|string',
    //         'supplier_balance' => 'required|numeric',
    //         'driver_id' => 'required|exists:drivers,id',
    //         'driver_name' => 'required|string',
    //         'trips' => 'required|integer',
    //         'balance' => 'required|numeric',
    //         'quantity' => 'required|numeric',
    //         'price_per_ton' => 'required|numeric',
    //     ]);

    //     Stock::create([
    //         'supplier_id' => $request->supplier_id,
    //         'supplier_name' => $request->supplier_name,
    //         'supplier_phone' => $request->supplier_phone,
    //         'supplier_balance' => $request->supplier_balance,
    //         'driver_id' => $request->driver_id,
    //         'driver_name' => $request->driver_name,
    //         'trips' => $request->trips,
    //         'balance' => $request->balance,
    //         'quantity' => $request->quantity,
    //         'price_per_ton' => $request->price_per_ton,
    //     ]);

    //     return response()->json(['success' => 'Stock record added successfully!']);
    // }
}
