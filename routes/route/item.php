<?php

use Illuminate\Support\Facades\Route;

// +-+-+-+-+-+-+-+- 商品 +-+-+-+-+-+-+-+-
use App\Http\Controllers\Item\Item\ItemController;
use App\Http\Controllers\Item\Item\ItemDownloadController;
// +-+-+-+-+-+-+-+- 商品アップロード +-+-+-+-+-+-+-+-
use App\Http\Controllers\Item\ItemUpload\ItemUploadController;

Route::middleware('common')->group(function (){
    // +-+-+-+-+-+-+-+- 商品 +-+-+-+-+-+-+-+-
    Route::controller(ItemController::class)->prefix('item')->name('item.')->group(function(){
        Route::get('', 'index')->name('index');
    });
    Route::controller(ItemDownloadController::class)->prefix('item_download')->name('item_download.')->group(function(){
        Route::get('download', 'download')->name('download');
    });
    // +-+-+-+-+-+-+-+- 商品アップロード +-+-+-+-+-+-+-+-
    Route::controller(ItemUploadController::class)->prefix('item_upload')->name('item_upload.')->group(function(){
        Route::get('', 'index')->name('index');
        Route::post('upload', 'upload')->name('upload');
        Route::get('error_download', 'error_download')->name('error_download');
    });
});