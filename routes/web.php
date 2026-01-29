<?php

use App\Http\Controllers\Applicant\CommunicationFormController;
use App\Http\Controllers\Applicant\DashboardController;
use App\Http\Controllers\Applicant\RequestController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\MagicLinkController;
use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Login', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
})->name('start');

Route::get('/requester/login/{token}', [MagicLinkController::class, 'authenticate'])
    ->name('applicant.magic-login');
Route::resource('/magic-link', MagicLinkController::class);

Route::middleware(['auth', 'role:applicant'])
    ->get('/dashboard', function () {
        return ['Dashboard Solicitante'];
    })
    ->name('applicant.dashboard');

Route::get('/calendar/events', [CalendarController::class, 'index'])
    ->name('calendar.events');

Route::middleware(['auth', 'role:applicant'])
    ->prefix('/applicant')
    ->group(function () {
        Route::resource('/dashboard', DashboardController::class)
            ->names('applicant.dashboard');

        Route::resource('/request', RequestController::class);

        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    });

require __DIR__ . '/auth.php';
