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
        // Validate input data
        $request->validate([
            'supplier_name' => 'required|string|max:255',
            'contact_person' => 'nullable|string|max:255',
            'phone' => 'nullable|numeric|digits_between:7,15',
            'email' => 'required|email|unique:suppliers,email',

            'address' => 'nullable|string|max:500',
        ]);

        // Store supplier data
        $supplier = Supplier::create([
            'supplier_name' => $request->supplier_name,
            'contact_person' => $request->contact_person,
            'phone' => $request->phone,
            'email' => $request->email,

            'address' => $request->address,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Supplier added successfully!',
            'data' => $supplier
        ]);
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

}
