<?php

namespace App\Http\Controllers;

use App\Models\Driver;
use App\Models\Invoice;
use App\Models\Customer;
use App\Models\Supplier;
use Illuminate\Http\Request;
use App\Models\PurchaseInvoice;
use Barryvdh\DomPDF\Facade\Pdf;

class PurchaseInvoiceController extends Controller
{


    public function newpurchase() {
        $customers = Customer::all();
        $suppliers = Supplier::all();
        $invoices = PurchaseInvoice::all();
        $drivers = Driver::all();

        return view('Invoices.NewPurchase',compact('invoices','customers','suppliers','drivers'));
    }
    public function create(){

        $customers = Customer::all();
        $suppliers = Supplier::all();
        $invoices = PurchaseInvoice::all();
        $drivers = Driver::all();

        return view('invoices.purchaseinvoice',compact('invoices','customers','suppliers','drivers'));
    }

    public function store(Request $request) {
        $validated = $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'driver_id' => 'required|exists:drivers,id',
            'date' => 'required|date',
            'quantity_tons' => 'required|numeric|min:0',
            'average_price_per_ton' => 'required|numeric|min:0',
            'total_balance' => 'required|numeric|min:0'
        ]);

        PurchaseInvoice::create($validated);

        return redirect()->route('P_invoices.create')->with('success', 'Invoice created successfully.');
    }


public function UpdateIP_nvoice(Request $request, $id)
    {
        // Validate request data
        $validated = $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'driver_id' => 'required|exists:drivers,id',
            'date' => 'required|date',
            'quantity_tons' => 'required|numeric|min:0',
            'average_price_per_ton' => 'required|numeric|min:0',
            'total_balance' => 'required|numeric|min:0'
        ]);

        // Find invoice by ID
        $P_invoice = PurchaseInvoice::findOrFail($id);



        // Update invoice details
        $P_invoice->update([
            'supplier_id' => $request->supplier_id,
            'driver_id' => $request->supplier_id,
            'date' => $request->date,
            'quantity_tons' => $request->quantity_tons,
            'average_price_per_ton' => $request->average_price_per_ton,
            'total_balance' => $request->total_balance,

        ]);

        // Return JSON response
        return response()->json([
            'success' => true,
            'message' => 'Purchase Invoice updated successfully!',
            'data' => $P_invoice
        ]);
    }


// public function generatePDF()
// {
//     $invoices = Invoice::with(['customer', 'supplier'])->get();

//     $pdf = Pdf::loadView('Invoices.invoicepdf', compact('invoices'));
//     return $pdf->download('invoices.pdf');
// }

public function DeleteP_Invoice(Request $request,$id){
    $P_invoice = PurchaseInvoice::find($id);
    if ($P_invoice) {
        $P_invoice->delete();
        return response()->json(['success' => 'Purhcase Invoice deleted successfully']);
    } else {
        return response()->json(['error' => 'Purhcase Invoice not found'], 404);
    }

}
}

