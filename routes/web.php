<?php

declare(strict_types=1);

use App\Http\Controllers\Site\ContactController;
use App\Http\Controllers\Site\ContractTrackerController;
use App\Http\Controllers\Site\HomeController;
use App\Http\Controllers\Site\JobApplicationController;
use App\Http\Controllers\Site\JobController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Website Public Routes (Site)
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home');

Route::prefix('site')->name('site.')->group(function (): void {
    Route::get('/jobs/filter', [JobController::class, 'filter'])->name('jobs.filter');
    Route::get('/jobs/{id}', [JobController::class, 'show'])->name('jobs.show');
    Route::post('/apply', [JobApplicationController::class, 'store'])->name('apply.store');
    Route::post('/tracker/track', [ContractTrackerController::class, 'track'])->name('tracker.track');
    Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');
});

/*
|--------------------------------------------------------------------------
| Admin Dashboard Routes
|--------------------------------------------------------------------------
*/
require __DIR__.'/admin.php';

/*
|--------------------------------------------------------------------------
| Accounting Dashboard Routes
|--------------------------------------------------------------------------
*/
require __DIR__.'/accounting.php';
