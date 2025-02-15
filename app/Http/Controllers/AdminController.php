<?php

namespace App\Http\Controllers;


use App\Models\Supplier;
use App\Models\Subjects;
use App\Models\User;
use App\Models\Parents;
use Illuminate\Http\Request;
use Illuminate\Container\Attributes\Auth;

class AdminController extends Controller
{

    public function dashboard(){
        $suppliers = Supplier::all();
        return view('admin.dashboard',compact('suppliers'));
    }

    public function managerdashboard(){
        $suppliers = Supplier::all();
        return view('manager.dashboard',compact('suppliers'));
    }
    public function SupplierManagement()
    {
       $suppliers = Supplier::paginate(5);

        return view('admin.suppliermanagement',compact('suppliers'));

    }

    public function StoreSupplier(Request $request)
    {
        // Validate input data
        $request->validate([
            'supplier_name'  => 'required|string|max:255',
            'contact_person' => 'nullable|string|max:255',
            'phone'          => 'nullable|numeric|digits_between:7,15',
            'email'          => 'required|email|unique:suppliers,email',
            'address'        => 'nullable|string|max:500',
        ]);

        // Store supplier data
        $supplier = Supplier::create([
            'supplier_name'  => $request->supplier_name,
            'contact_person' => $request->contact_person,
            'phone'          => $request->phone,
            'email'          => $request->email,
            'address'        => $request->address,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Supplier added successfully!',
            'data'    => $supplier
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

public function UpdateSupplier(Request $request, $id) {


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
        'message' => 'Class updated successfully!',
        'data' => $supplier
    ]);
}




}


