<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SupplierController extends Controller
{
    public function SupplierManagement(Request $request)
    {

        $suppliers = Supplier::paginate(5);

        return view('Supplier.SupplierManagement', compact('suppliers'));
    }
    public function searchSuppliers(Request $request)
    {
        $query = $request->input('phone');

        $suppliers = Supplier::where('phone', 'LIKE', "%$query%")->get();

        return response()->json($suppliers);
    }


    public function StoreSupplier(Request $request)
    {
        $validated = $request->validate([
            'supplier_name' => 'required|string|max:255',
            'contact_person' => 'nullable|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'balance' => 'nullable|numeric',
            'address' => 'nullable|string|max:500',
        ]);

        $supplier = Supplier::create($validated);

        return response()->json(['supplier' => $supplier]);
    }


    public function DeleteSupplier($id)
    {
        $supplier = Supplier::find($id);
        if ($supplier) {

            $supplier->delete();
            return response()->json(['success' => 'Supplier deleted successfully']);
        } else {
            return response()->json(['error' => 'Supplier not found'], 404);
        }
    }

    public function UpdateSupplier(Request $request, $id)
    {

        $supplier = Supplier::findOrFail($id);
        $supplier->update([
            'supplier_name' => $request->supplier_name,
            'contact_person' => $request->contact_person,
            'phone' => $request->phone,
            'email' => $request->email,

            'address' => $request->address,

        ]);
        return response()->json([
            'success' => true,
            'message' => 'Supplier updated successfully!',
            'data' => $supplier
        ]);
    }
    public function searchByPhone(Request $request)
    {
        $phone = $request->phone;

        $suppliers = Supplier::where('phone', 'LIKE', "%$phone%")->get();

        return response()->json([
            'success' => true,
            'suppliers' => $suppliers
        ]);
    }


}
