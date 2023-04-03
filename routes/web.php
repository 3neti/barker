<?php

use App\Http\Controllers\CurrentCampaignController;
use App\Http\Controllers\CampaignController;
use App\Http\Controllers\CheckinController;
use App\Http\Controllers\ContactController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');
    Route::resource('campaigns', CampaignController::class)
        ->only(['create', 'store']);
    Route::put('/current-campaign', [CurrentCampaignController::class, 'update'])->name('current-campaign.update');
    Route::resource('checkins', CheckinController::class)
        ->only(['index', 'create', 'store', 'show']);
});

Route::webhooks('webhook-paynamics-paybiz', 'paynamics-paybiz');

Route::resource('contacts', ContactController::class)
    ->only(['show']);
