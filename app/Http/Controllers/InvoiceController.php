<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Customer;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class InvoiceController extends Controller
{
    public function create(){

        $customers = Customer::all();
        $suppliers = Supplier::all();
        $invoices = Invoice::all();
        return view('invoices.invoice',compact('invoices','customers','suppliers'));
    }
    public function store(Request $request)
{
    $res = $request->validate([
        'customer_id' => 'nullable|exists:customers,id',
        'supplier_id' => 'nullable|exists:suppliers,id',
        'invoice_number' => 'required',
        'invoice_date' => 'required|date',
        'total_amount' => 'required|numeric|min:0',
        'amount_paid' => 'required|numeric|min:0',
        'balance_due' => 'required|numeric|min:0',
        'payment_status' => 'required|in:Pending,Paid',
    ]);


  $res = Invoice::create([
    'customer_id' => $request->customer_id,
    'supplier_id' => $request->supplier_id,
    'invoice_number' => $request->invoice_number,
    'invoice_date' => $request->invoice_date,
    'total_amount' => $request->total_amount,
    'amount_paid' => $request->amount_paid,
    'balance_due' => $request->balance_due,
    'payment_status' => $request->payment_status,
]);
    return redirect()->route('invoices.create')->with('success', 'Invoice created successfully!');
}

public function UpdateInvoice(Request $request, $id)
    {
        // Validate request data
        $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'supplier_id' => 'required|exists:suppliers,id',
            'invoice_date' => 'required|date',
            'total_amount' => 'required|numeric|min:0',
            'amount_paid' => 'required|numeric|min:0',
            'balance_due' => 'required|numeric|min:0',
            'payment_status' => 'required|in:Pending,Paid',
        ]);

        // Find invoice by ID
        $invoice = Invoice::findOrFail($id);

        // Update invoice details
        $invoice->update([
            'customer_id' => $request->customer_id,
            'supplier_id' => $request->supplier_id,
            'invoice_date' => $request->invoice_date,
            'total_amount' => $request->total_amount,
            'amount_paid' => $request->amount_paid,
            'balance_due' => $request->balance_due,
            'payment_status' => $request->payment_status,
        ]);

        // Return JSON response
        return response()->json([
            'success' => true,
            'message' => 'Invoice updated successfully!',
            'data' => $invoice
        ]);
    }


public function generatePDF()
{
    $invoices = Invoice::with(['customer', 'supplier'])->get();

    $pdf = Pdf::loadView('Invoices.invoicepdf', compact('invoices'));
    return $pdf->download('invoices.pdf');
}

public function DeleteInvoice(Request $request,$id){
    $invoice = Invoice::find($id);
    if ($invoice) {
        $invoice->delete();
        return response()->json(['success' => 'Invoice deleted successfully']);
    } else {
        return response()->json(['error' => 'Invoice not found'], 404);
    }

}
}

