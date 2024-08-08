<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ProjectsController;
use App\Http\Controllers\TicketsController;
use App\Http\Controllers\CommentsController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\CompanyController;
use App\Models\Project;
use App\Models\Company;
use App\Models\User;
use App\Models\Ticket;
use App\Models\Comment;
use App\Models\Attachment;


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
    // Recall project, companies, users, tickets, comments, attachments
    $projects = Project::all();
    $users = User::all();
    $customers = User::role('customer')->get();
    $companies = Company::all();
    $tickets = Ticket::all();
    $comments = Comment::all();
    $attachments = Attachment::all();

    return view('dashboard', compact('projects', 'companies', 'users', 'customers', 'tickets', 'comments', 'attachments'));
})->middleware(['auth', 'verified'])->name('dashboard');

// Routes for the TicketsController
Route::get('/tickets', [TicketsController::class, 'index'])->name('tickets.index');
Route::get('/tickets/nuovo', [TicketsController::class, 'create'])->name('tickets.create');
Route::patch('/tickets', [TicketsController::class, 'filter'])->name('tickets.filter');
Route::post('/tickets/nuovo', [TicketsController::class, 'store'])->name('tickets.store');
Route::get('/tickets/{ticket}', [TicketsController::class, 'show'])->name('tickets.show');

// Routes for the CommentsController
Route::post('/tickets/{ticket}', [CommentsController::class, 'store'])->name('comments.store');

// Routes for the ProjectsController
Route::get('/progetti/nuovo', [ProjectsController::class, 'create'])->name('projects.create');
Route::post('/progetti', [ProjectsController::class, 'store'])->name('projects.store');
Route::get('/progetti', [ProjectsController::class, 'index'])->name('projects.index');
Route::patch('/progetti', [ProjectsController::class, 'filter'])->name('projects.filter');
Route::get('/progetti/{project}', [ProjectsController::class, 'show'])->name('projects.show');

// Routes for profile (all roles)
Route::get('/profilo', [ProfileController::class, 'show'])->name('profile.show');
Route::get('/profilo/notifiche', [ProfileController::class, 'notifications'])->name('profile.notifications');
Route::delete('/profilo/notifiche/{id}', [ProfileController::class, 'notificationDestroySingle'])->name('profile.notificationDestroySingle');
Route::delete('/profilo/notifiche', [ProfileController::class, 'notificationDestroy'])->name('profile.notificationDestroy');

Route::group(['middleware' => ['role:team']], function () {
    // Routes for settings
    Route::get('/impostazioni', [SettingsController::class, 'index'])->name('settings.index');
    Route::post('/impostazioni', [SettingsController::class, 'update'])->name('settings.update');

    Route::get('/profilo', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profilo', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profilo', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Routes for Team
    Route::get('/team', function () {return view('team.index');})->name('team');
    Route::get('/team/{team}', [ProfileController::class, 'show'])->name('team.show');

    // Routes for the CompanyController
    Route::get('/aziende', [CompanyController::class, 'index'])->name('companies.index');
    // Route::get('/aziende', [CompanyController::class, 'search'])->name('companies.search');
    Route::get('/aziende/nuova', [CompanyController::class, 'create'])->name('companies.create');
    Route::post('/aziende', [CompanyController::class, 'store'])->name('companies.store');
    Route::get('/aziende/{company}', [CompanyController::class, 'show'])->name('companies.show');
    Route::get('/aziende/{company}/modifica', [CompanyController::class, 'edit'])->name('companies.edit');
    Route::patch('/aziende/{company}', [CompanyController::class, 'update'])->name('companies.update');
    Route::get('/aziende/{company}/deletelogo', [CompanyController::class, 'delete_logo'])->name('companies.delete_logo');
    Route::delete('/aziende/{company}', [CompanyController::class, 'destroy'])->name('companies.destroy');

    // Routes for the CustomerController
    Route::get('/clienti', [CustomerController::class, 'index'])->name('customers.index');
    // Route::get('/clienti', [CustomerController::class, 'search'])->name('customers.search');
    Route::patch('/clienti', [CustomerController::class, 'filter'])->name('customers.filter');
    Route::get('/clienti/nuovo', [CustomerController::class, 'create'])->name('customers.create');
    Route::post('/clienti', [CustomerController::class, 'store'])->name('customers.store');
    Route::get('/clienti/{customer}', [CustomerController::class, 'show'])->name('customers.show');
    Route::get('/clienti/{customer}/modifica', [CustomerController::class, 'edit'])->name('customers.edit');
    Route::post('/clienti/{id}', [CustomerController::class, 'update'])->name('customers.update');
    Route::delete('/clienti/{id}', [CustomerController::class, 'destroy'])->name('customers.destroy');

    Route::get('/progetti/{project}/modifica', [ProjectsController::class, 'edit'])->name('projects.edit');
    Route::patch('/progetti/{id}', [ProjectsController::class, 'update'])->name('projects.update');
    Route::delete('/progetti/{id}', [ProjectsController::class, 'destroy'])->name('projects.destroy');

    // Routes for the TicketsController
    Route::get('/tickets/{ticket}/chiudi', [TicketsController::class, 'close'])->name('tickets.close');
    Route::delete('/tickets/{id}', [TicketsController::class, 'destroy'])->name('tickets.destroy');
});

require __DIR__.'/auth.php';