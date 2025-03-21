<?php

use App\Http\Controllers\{
    AdminController,
    AuthController,
    DriverController,
    ExpenseController,
    InvoiceController,
    PurchaseController,
    PurchaseInvoiceController,
    StockController,
    SupplierController,
    VehicleController
};
use Illuminate\Support\Facades\Route;

// Redirect root to login page
Route::get('/', fn() => redirect()->route('login.form'));

// Unauthorized Access Route
Route::get('/unauth', fn() => view('unauthorized'))->name('unauthorized');

// Authentication Routes
Route::controller(AuthController::class)->group(function () {
    Route::get('/login', 'index')->name('login.form');
    Route::post('/login', 'login')->name('login.submit');
    Route::post('/logout', 'logout')->name('logout');
    Route::get('/registrationView', 'registrationView')->name('registrationView');
    Route::post('/register', 'register')->name('register');
});

// Grouped routes for admin with authentication and role check
Route::middleware(['auth', 'role:admin'])->group(function () {

    // Admin Dashboard
    Route::get('/AdminDashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/adminprofile', [AuthController::class, 'profile'])->name('admin.profile');

    /*
    |--------------------------------------------------------------------------
    | Supplier Management Routes
    |--------------------------------------------------------------------------
    */
    Route::controller(SupplierController::class)->group(function () {
        Route::get('/supplier-management', 'SupplierManagement')->name('supplier.management');
        Route::get('/search-suppliers', 'searchSuppliers')->name('supplier.search');
        Route::post('/store-supplier', 'StoreSupplier')->name('supplier.store');
        Route::put('/update-supplier/{id}', 'UpdateSupplier')->name('supplier.update');
        Route::delete('/delete-supplier/{id}', 'DeleteSupplier')->name('supplier.delete');
        Route::get('/search-supplier', 'searchByPhone')->name('supplier.searchByPhone');


    });

    /*
    |--------------------------------------------------------------------------
    | Driver Management Routes
    |--------------------------------------------------------------------------
    */
    Route::controller(DriverController::class)->group(function () {
        Route::get('/DriverCreate', 'create')->name('driver.create');
        Route::post('/DriverStore', 'DriverStore')->name('driver.driverStore');
        Route::delete('/DeleteDriver/{id}', 'DeleteDriver')->name('driver.deleteDriver');
        Route::put('/UpdateDriver/{id}', 'UpdateDriver')->name('driver.UpdateDriver');
        Route::post('/update-balance', 'updateBalance')->name('update.balance');


        Route::get('/DriverDetailsCreate', 'DriverDetailsCreate')->name('driver.DriverDetailsCreate');
        Route::post('/driver-details/store', 'storeDriverDetails')->name('driver.details.store');
        Route::post('/process-payment', 'processPayment')->name('payment.process');
        Route::put('/driver-details/update', 'update')->name('driver.details.update');
        Route::delete('/driver-details/delete', 'destroy')->name('driver.details.delete');
        Route::get('/generate-pdf/{id}', 'generatePDF')->name('driver.generatepdf');
        Route::get('/drivers/search', 'searchByPhone')->name('driver.searchByPhone');

    });

    /*
    |--------------------------------------------------------------------------
    | Purchase Management Routes
    |--------------------------------------------------------------------------
    */
    Route::controller(PurchaseController::class)->group(function () {
        Route::get('/Newpurchase', 'newpurchase')->name('newpurchase');
        Route::post('/purchase/store', 'store')->name('purchase.store');
        Route::post('/purchase/delete', 'deletePurchase')->name('purchase.delete');
        Route::get('/generate-pdf', 'generatePDF')->name('purchase.pdf');
        // Route::post('/process-payment', 'processPayment')->name('payment.process');
        Route::put('/UpdatePurchase/{id}', 'UpdatePurchase')->name('purchase.UpdatePurchase');
    });

      /*
    |--------------------------------------------------------------------------
    | Customer Management Routes
    |--------------------------------------------------------------------------

    Route::controller(AdminController::class)->group(function () {
        Route::get('/CustomerManagement', 'CustomerManagement')->name('admin.CustomerManagement');
        Route::post('/StoreCustomer', 'StoreCustomer')->name('admin.storecustomer');
        Route::get('/searchCustomer', 'searchCustomer')->name('admin.searchCustomer');
        Route::delete('/DeleteCustomer/{id}', 'DeleteCustomer')->name('admin.deletecustomer');
        Route::put('/UpdateCustomer/{id}', 'UpdateCustomer')->name('admin.updateCustomer');
    });
    */


});


