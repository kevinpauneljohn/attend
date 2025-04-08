<?php
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    Route::resource('employees', \App\Http\Controllers\EmployeeController::class);
    Route::post('/connect-employee/{employee_id}', [\App\Http\Controllers\EmployeeController::class, 'connect_employee_to_biometrics_form'])->name('connect-employee');
});
