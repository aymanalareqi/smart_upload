<?php

use Alareqi\SmartUpload\Http\Controllers\TemporaryUploadController;
use Alareqi\SmartUpload\Http\Controllers\TusController;
use Illuminate\Support\Facades\Route;

Route::prefix('api')->group(function () {
    Route::post('/upload-file', [TemporaryUploadController::class, 'uploadFile']);
    Route::any('/tus/{any?}', [TusController::class, 'handle'])->where('any', '.*');
});
