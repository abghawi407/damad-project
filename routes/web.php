<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Ward\PatientController;
use App\Http\Controllers\Nutrition\RequestController as NutritionRequestController;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Ward
    Route::get('/ward/patients', [PatientController::class, 'index'])->name('ward.patients.index');
    Route::post('/ward/patients', [PatientController::class, 'store'])->name('ward.patients.store');
    Route::put('/ward/patients/{patient}', [PatientController::class, 'update'])->name('ward.patients.update');

    // Nutrition
    Route::get('/nutrition/requests', [NutritionRequestController::class, 'index'])->name('nutrition.requests.index');
    Route::post('/nutrition/requests/{id}/approve', [NutritionRequestController::class, 'approve'])->name('nutrition.requests.approve');
    Route::post('/nutrition/requests/{id}/print', [NutritionRequestController::class, 'printLabel'])->name('nutrition.requests.print');
});

require __DIR__.'/auth.php';