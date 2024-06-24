<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;

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
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});



// Dashboard not accessible to guests
// Route::middleware('auth')->get('/', function () {
//     if (auth()->user()->hasRole('customer') || auth()->user()->hasRole('team')) {
//         return view('dashboard');
//     } else {
//         return view('accedi');
//     }
// })->name('dashboard');

Route::middleware('role:customer')->group(function () {
    Route::get('/tickets', function () {
        return view('tickets');
    })->name('tickets');
});

Route::middleware('role:team')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/progetti', function () {
        return view('progetti');
    })->name('progetti');

    Route::get('/clienti', function () {
        return view('clienti');
    })->name('clienti');
});

require __DIR__.'/auth.php';