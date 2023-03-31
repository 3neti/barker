<?php

use App\Http\Controllers\CheckinController;
use App\Actions\Hyperverge\ProcessResult;
use Illuminate\Support\Facades\Route;

Route::get('hyperverge-api/result', ProcessResult::class)->name('hyperverge-result');
