<?php

use App\Http\Controllers\Applicant\DashboardController as ApplicantDashboard;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Applicant\RequestController as ApplicantRequest;
use App\Http\Controllers\Admin\RequestController as AdminRequest;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\MagicLinkController;
#use App\Http\Controllers\ProfileController;
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

Route::post('login', [AuthController::class, 'login'])->name('login');
Route::post('logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/requester/login/{token}', [MagicLinkController::class, 'authenticate'])
    ->name('applicant.magic-login');
Route::resource('/magic-link', MagicLinkController::class);

Route::get('/calendar/events', [CalendarController::class, 'index'])
    ->name('calendar.events');

Route::get('/attachments/{attachment}/download', function (
    \App\Models\RequestAttachment $attachment
) {
    return response()->download(
        storage_path('app/public/' . $attachment->path),
        $attachment->original_name
    );
})->name('attachments.download');

Route::middleware(['auth', 'role:applicant'])
    ->prefix('/applicant')
    ->group(function () {
        Route::resource('/dashboard', ApplicantDashboard::class)
            ->names('applicant.dashboard');

        Route::resource('/request', ApplicantRequest::class);
    });

Route::middleware(['auth', 'role:admin|directorship'])
    ->prefix('/manager')
    ->group(function () {
        Route::resource('/dashboard', AdminDashboard::class)
            ->names('admin.dashboard');
        Route::resource('/requests', AdminRequest::class)
            ->names('admin.manager');
    });


/*Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');*/
