<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;



Route::get('/loginView', [AuthController::class, 'loginView'])->name('loginView');
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

        //   Route::delete('/DeleteSupplier/{id}', 'DeleteSupplier')->name('admin.deleteSupplier');
        //   Route::put('/UpdateSupplier/{id}', 'UpdateSupplier')->name('admin.updateSupplier');
    });
    Route::get('/adminprofile', [AuthController::class, 'profile'])->name('admin.profile');
});


// Route::middleware(['auth', 'role:manager'])->group(function () {
//     Route::controller(AdminController::class)->group(function () {
//         Route::get('/ManagerDashboard', 'managerdashboard')->name('manager.dashboard');
//         Route::get('/SupplierMan', 'SupplierManagement')->name('manager.SupplierManagement');
//         Route::post('/StoreSup', 'StoreSupplier')->name('manager.storesupplier');
//         Route::get('/managerprofile', [AuthController::class, 'profile'])->name('manager.profile');

//     });
// });

Route::get('/unauthorized', function () {
    return view('unauthorized');
})->name('unauthorized');










