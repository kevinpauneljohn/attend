<?php
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    Route::resource('attendances', \App\Http\Controllers\AttendanceController::class);
});
