<?php

use App\Models\Driver;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\DriverController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\VehicleController;
use App\Http\Controllers\PurchaseInvoiceController;

Route::get('/loginView', [AuthController::class, 'index'])->name('loginView');
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/registrationView', [AuthController::class, 'registrationView'])->name('registrationView');
Route::post('/register', [AuthController::class, 'register'])->name('register');


Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::controller(AdminController::class)->group(function () {
        Route::get('/AdminDashboard', 'dashboard')->name('admin.dashboard');
        // Supplier Rutes
        Route::get('/SupplierManagement', 'SupplierManagement')->name('admin.SupplierManagement');
        Route::post('/StoreSupplier', 'StoreSupplier')->name('admin.storesupplier');
        Route::delete('/DeleteSupplier/{id}', 'DeleteSupplier')->name('admin.deleteSupplier');
        Route::put('/UpdateSupplier/{id}', 'UpdateSupplier')->name('admin.updateSupplier');
        Route::get('/admin/supplier-management', [AdminController::class, 'SupplierManagement'])->name('supplier.management');
        Route::get('/search-suppliers', [AdminController::class, 'searchSuppliers'])->name('admin.searchSuppliers');
        // Customer Routes
        Route::get('/CustomerManagement', 'CustomerManagement')->name('admin.CustomerManagement');
        Route::post('/StoreCustomer', 'StoreCustomer')->name('admin.storecustomer');
        Route::get('/searchCustomer', [AdminController::class, 'searchCustomer'])->name('admin.searchCustomer');
        Route::delete('/DeleteCustomer/{id}', 'DeleteCustomer')->name('admin.deletecustomer');
        Route::put('/UpdateCustomer/{id}', 'UpdateCustomer')->name('admin.updateCustomer');
    });
    Route::get('/adminprofile', [AuthController::class, 'profile'])->name('admin.profile');

    //Invoice
    Route::get('/invoicesCreate', [InvoiceController::class, 'create'])->name('invoices.create');
    Route::post('/invoicesStore', [InvoiceController::class, 'store'])->name('invoices.store');
    Route::get('/invoices/pdf', [InvoiceController::class, 'generatePDF'])->name('invoices.pdf');
    Route::delete('/deleteInvoice/{id}', [InvoiceController::class, 'DeleteInvoice'])->name('invoices.deleteInvoice');
    Route::put('/UpdateInvoice/{id}', [InvoiceController::class, 'UpdateInvoice'])->name('invoices.updateInvoice');

    // Driver Routes
    Route::get('/DriverCreate', [DriverController::class, 'create'])->name('driver.create');
    Route::post('/DriverStore', [DriverController::class, 'DriverStore'])->name('driver.driverStore');
    // Route::get('/invoices/pdf', [InvoiceController::class, 'generatePDF'])->name('invoices.pdf');
    Route::delete('/DeleteDriver/{id}', [DriverController::class, 'DeleteDriver'])->name('driver.deleteDriver');
    Route::put('/UpdateDriver/{id}', [DriverController::class, 'UpdateDriver'])->name('driver.UpdateDriver');
    Route::post('/update-balance', [DriverController::class, 'updateBalance'])->name('update.balance');


    //   //Purchase Invoice
    //   Route::get('/Newpurchase', [PurchaseInvoiceController::class, 'newpurchase'])->name('newpurchase');

    //   Route::get('/PurchaseinvoicesCreate', [PurchaseInvoiceController::class, 'create'])->name('P_invoices.create');
    //   Route::post('/PurchaseinvoicesStore', [PurchaseInvoiceController::class, 'store'])->name('P_invoices.store');
    // //   Route::get('/invoices/pdf', [InvoiceController::class, 'generatePDF'])->name('invoices.pdf');
    //   Route::delete('/deleteP_Invoice/{id}', [PurchaseInvoiceController::class, 'DeleteP_Invoice'])->name('P_invoices.deleteP_Invoice');
    //   Route::put('/UpdateP_Invoice/{id}', [PurchaseInvoiceController::class, 'UpdateIP_nvoice'])->name('P_invoices.updateP_Invoice');

    //stock
    Route::get('/Newpurchase', [StockController::class, 'newpurchase'])->name('newpurchase');
    Route::get('/Stock', [StockController::class, 'Stock'])->name('stock');
    Route::post('/stock/store', [StockController::class, 'store'])->name('stock.store');
    Route::get('/stock/pdf', [StockController::class, 'generatePDF'])->name('stock.pdf');

    //Expanses
    Route::get('/expenses', [ExpenseController::class, 'index'])->name('expenses.index');
    Route::post('/expensestore', [ExpenseController::class, 'expensestore'])->name('expense.store');
    Route::delete('/DeleteExpense/{id}', [ExpenseController::class, 'DeleteExpense'])->name('expense.delete');
    Route::get('/stock/pdf', [StockController::class, 'generatePDF'])->name('stock.pdf');

});
Route::get('/unauthorized', function () {
    return view('unauthorized');
})->name('unauthorized');

// Route::middleware(['auth', 'role:manager'])->group(function () {
//     Route::controller(AdminController::class)->group(function () {
//         Route::get('/ManagerDashboard', 'managerdashboard')->name('manager.dashboard');
//         Route::get('/SupplierMan', 'SupplierManagement')->name('manager.SupplierManagement');
//         Route::post('/StoreSup', 'StoreSupplier')->name('manager.storesupplier');
//         Route::get('/managerprofile', [AuthController::class, 'profile'])->name('manager.profile');

//     });
// });
