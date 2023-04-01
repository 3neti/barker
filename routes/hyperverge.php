<?php

use App\Http\Controllers\CheckinController;
use App\Actions\Hyperverge\RetrieveResult;
use Illuminate\Support\Facades\Route;

Route::get('hyperverge-api/result', RetrieveResult::class)->name('hyperverge-result');
