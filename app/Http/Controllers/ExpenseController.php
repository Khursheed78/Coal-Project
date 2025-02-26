<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\Supplier;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{

    public function index()
    {
        $expenses = Expense::with('supplier')->get(); // Fetch expenses with related suppliers
        $suppliers = Supplier::all(); // Fetch all suppliers for the dropdown

        return view('expenses.expense', compact('expenses', 'suppliers'));
    }



     public function expensestore(Request $request)
     {
         $request->validate([
             'title' => 'required',
             'amount' => 'required|numeric',
             'expense_date' => 'required|date',
             'description' => 'required',
             'supplier_id' => 'required|exists:suppliers,id',
         ]);

         Expense::create($request->all());

         return redirect()->route('expenses.index')->with('success', 'Expense added successfully.');
     }


    public function update(Request $request, Expense $expense)
    {
        $request->validate([
            'title' => 'required',
            'amount' => 'required|numeric',
            'expense_date' => 'required|date',
            'description' => 'required',
            'supplier_id' => 'required|exists:suppliers,id',
        ]);

        $expense->update($request->all());

        return redirect()->route('expenses.index')->with('success', 'Expense updated successfully.');
    }


    public function DeleteExpense($id)
{
    $expense = Expense::find($id);
    if ($expense) {
        $expense->delete();
        return response()->json(['success' => 'expense deleted successfully']);
    } else {
        return response()->json(['error' => 'expense not found'], 404);
    }
}

}
