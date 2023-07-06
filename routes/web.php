<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    if (Auth::check()) {
        return redirect('/home');
    } else {
        return view('welcome');
    }
})->middleware('guest');


Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home')->middleware('auth');
Route::get('/search', [App\Http\Controllers\HomeController::class, 'search'])->middleware('auth');
Route::get('/export', [App\Http\Controllers\HomeController::class, 'exportToExcel'])->middleware('auth');


Route::middleware(['auth', 'role:operator'])->group(function () {
    Route::get('/upload-data', [App\Http\Controllers\UploadDataController::class, 'index'])->name('upload-data')->middleware('auth');
    Route::post('/upload-data', [App\Http\Controllers\UploadDataController::class, 'upload'])->name('upload')->middleware('auth');
});

Route::get('/profile', [App\Http\Controllers\ProfileController::class, 'index'])->name('profile')->middleware('auth');
Route::get('/profile/edit', [App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit')->middleware('auth');
Route::put('/profile', [App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update')->middleware('auth');

// Route::get('/sales-order', [App\Http\Controllers\SalesOrder22Controller::class, 'index'])->name('sales-order.index')->middleware('auth', 'verified');
Route::get('/sales-order/{tahun}', [App\Http\Controllers\SalesOrderController::class, 'show'])->name('sales-order.tahun')->middleware('auth');
Route::get('/sales-order/{tahun}/search', [App\Http\Controllers\SalesOrderController::class, 'search'])->name('sales-order-search.tahun')->middleware('auth');
Route::get('/sales-order/{tahun}/export', [App\Http\Controllers\SalesOrderController::class, 'exportToExcel'])->name('sales-order-export.tahun')->middleware('auth');
Route::get('/sales-order/{tahun}/exportSPK', [App\Http\Controllers\SalesOrderController::class, 'exportSPK'])->name('sales-order.exportSPK.tahun')->middleware('auth');
Route::get('/sales-order/{tahun}/exportWO', [App\Http\Controllers\SalesOrderController::class, 'exportWO'])->name('sales-order.exportWO.tahun')->middleware('auth');
Route::get('/sales-order/{tahun}/exportRFI', [App\Http\Controllers\SalesOrderController::class, 'exportRFI'])->name('sales-order.exportRFI.tahun')->middleware('auth');


Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/user-management', [App\Http\Controllers\UserManagementController::class, 'index'])->name('user-management')->middleware('auth',);
    Route::get('/user-management/create', [App\Http\Controllers\UserManagementController::class, 'create'])->name('user-management.create')->middleware('auth');
    Route::post('/user-management', [App\Http\Controllers\UserManagementController::class, 'store'])->name('user-management.store')->middleware('auth');
    Route::get('/user-management/edit', [App\Http\Controllers\UserManagementController::class, 'edit'])->name('user-management.edit')->middleware('auth');
    Route::put('/user-management', [App\Http\Controllers\UserManagementController::class, 'update'])->name('user-management.update')->middleware('auth');
    Route::delete('/user-management/{user}', [App\Http\Controllers\UserManagementController::class, 'destroy'])->name('user-management.destroy')->middleware('auth');
    Route::get('/sales-order/{tahun}/delete', [App\Http\Controllers\SalesOrderController::class, 'deleteByYear'])->name('sales-order-delete.tahun')->middleware('auth');
});
