<?php

use Alareqi\SmartUpload\Http\Controllers\TemporaryUploadController;
use Illuminate\Support\Facades\Route;

Route::prefix('upload')->group(function () {
    Route::post('/init', [TemporaryUploadController::class, 'init']);
    Route::post('/{uuid}/finish', [TemporaryUploadController::class, 'finish']);
    Route::delete('/{uuid}', [TemporaryUploadController::class, 'cancel']);
});
