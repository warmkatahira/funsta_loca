<?php

use Illuminate\Support\Facades\Route;

// +-+-+-+-+-+-+-+- ダッシュボード +-+-+-+-+-+-+-+-
use App\Http\Controllers\Dashboard\DashboardController;

Route::middleware('common')->group(function (){
    // -+-+-+-+-+-+-+-+-+-+-+-+ ダッシュボード -+-+-+-+-+-+-+-+-+-+-+-+
    Route::controller(DashboardController::class)->prefix('dashboard')->name('dashboard.')->group(function(){
        Route::get('', 'index')->name('index');
    });
});