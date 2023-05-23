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


Auth::routes(['verify'=> true]);

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home')->middleware('auth', 'verified');

Route::middleware(['auth', 'role:operator'])->group(function () {
    Route::get('/upload-data', [App\Http\Controllers\UploadDataController::class, 'index'])->name('upload-data')->middleware('auth', 'verified');
    Route::post('/upload-data/success', [App\Http\Controllers\UploadDataController::class, 'upload'])->name('upload')->middleware('auth', 'verified');
});

Route::get('/profile', [App\Http\Controllers\ProfileController::class, 'index'])->name('profile')->middleware('auth', 'verified');
Route::get('/profile/edit', [App\Http\Controllers\ProfileController::class, 'edit'])->name('profile.edit')->middleware('auth', 'verified');
Route::put('/profile', [App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update')->middleware('auth', 'verified');

Route::get('/monitoringSO22', [App\Http\Controllers\SalesOrder22Controller::class, 'index'])->name('salesorder22')->middleware('auth', 'verified');

Route::get('/monitoringSO23', [App\Http\Controllers\SalesOrder23Controller::class, 'index'])->name('salesorder23')->middleware('auth', 'verified');

Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/user-management', [App\Http\Controllers\UserManagementController::class, 'index'])->name('user-management')->middleware('auth', 'verified');
    Route::get('/user-management/edit', [App\Http\Controllers\UserManagementController::class, 'edit'])->name('user-management.edit')->middleware('auth', 'verified');
    Route::put('/user-management', [App\Http\Controllers\UserManagementController::class, 'update'])->name('user-management.update')->middleware('auth', 'verified');
    Route::delete('/user-management/{user}', [App\Http\Controllers\UserManagementController::class, 'destroy'])->name('user-management.destroy')->middleware('auth', 'verified');
});

