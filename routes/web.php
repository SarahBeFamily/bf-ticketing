<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ProjectsController;

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


Route::group(['middleware' => ['role:customer,team']], function () {
    Route::get('/tickets', function () {
        return view('tickets');
    })->name('tickets');
});

Route::group(['middleware' => ['role:team']], function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Routes for Team
    Route::get('/team', function () {return view('team.index');})->name('team');
    Route::get('/team/{team}', [ProfileController::class, 'show'])->name('team.show');

    // Routes for the CustomerController
    Route::get('/clienti', [CustomerController::class, 'index'])->name('customers.index');
    Route::get('/clienti/nuovo', [CustomerController::class, 'create'])->name('customers.create');
    Route::post('/clienti', [CustomerController::class, 'store'])->name('customers.store');
    Route::get('/clienti/{customer}', [CustomerController::class, 'show'])->name('customers.show');
    Route::get('/clienti/{customer}/modifica', [CustomerController::class, 'edit'])->name('customers.edit');
    Route::post('/clienti/{id}', [CustomerController::class, 'update'])->name('customers.update');
    Route::delete('/clienti/{id}', [CustomerController::class, 'destroy'])->name('customers.destroy');

    // Routes for the ProjectsController
    Route::get('/progetti', [ProjectsController::class, 'index'])->name('projects.index');
    Route::patch('/progetti', [ProjectsController::class, 'sort'])->name('projects.sort');
    Route::patch('/progetti', [ProjectsController::class, 'filter'])->name('projects.filter');
    Route::get('/progetti/nuovo', [ProjectsController::class, 'create'])->name('projects.create');
    Route::post('/progetti', [ProjectsController::class, 'store'])->name('projects.store');
    Route::get('/progetti/{project}', [ProjectsController::class, 'show'])->name('projects.show');
    Route::get('/progetti/{project}/modifica', [ProjectsController::class, 'edit'])->name('projects.edit');
    Route::patch('/progetti/{id}', [ProjectsController::class, 'update'])->name('projects.update');
    Route::delete('/progetti/{id}', [ProjectsController::class, 'destroy'])->name('projects.destroy');
});

require __DIR__.'/auth.php';