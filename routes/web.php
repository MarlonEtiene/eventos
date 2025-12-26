<?php

use App\Http\Controllers\Applicant\DashboardController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\MagicLinkController;
use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('login', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/requester/login/{token}', [MagicLinkController::class, 'authenticate'])
    ->name('applicant.magic-login');
Route::resource('/magic-link', MagicLinkController::class);

/*Route::get('/form/event', function () {
    return Inertia::render('event-form');
});*/

/*Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');*/

Route::middleware(['auth', 'role:applicant'])
    ->get('/applicant/dashboard', function () {
        return ['Dashboard Solicitante'];
    })
    ->name('applicant.dashboard');

Route::middleware(['auth', 'role:applicant'])->group(function () {
    Route::resource('/applicant/dashboard', DashboardController::class)
        ->names('applicant.dashboard');

    Route::get('/calendar/events', [CalendarController::class, 'index'])
        ->name('calendar.events');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
