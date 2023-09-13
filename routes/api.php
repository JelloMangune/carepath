<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BarangayController;
use App\Http\Controllers\InfantController;
use App\Http\Controllers\VaccineController;
use App\Http\Controllers\VaccineDoseController;
use App\Http\Controllers\ImmunizationRecordController;

// Public routes (no authentication required)
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Private routes (authentication required)
Route::middleware('auth:sanctum')->group(function () {
    // User resource routes
    Route::get('/users', [UserController::class, 'index']); // Get all users
    Route::get('/users/{id}', [UserController::class, 'show']); // Get a single user by ID
    Route::put('/users/{id}', [UserController::class, 'update']); // Update a user by ID
    Route::delete('/users/{id}', [UserController::class, 'destroy']); // Delete a user by ID

    // Barangay resource routes
    Route::get('/barangays', [BarangayController::class, 'index']); // Get all barangays
    Route::get('/barangays/{id}', [BarangayController::class, 'show']); // Get a single barangay by ID
    Route::post('/barangays', [BarangayController::class, 'store']); // Create a new barangay
    Route::put('/barangays/{id}', [BarangayController::class, 'update']); // Update a barangay by ID
    Route::delete('/barangays/{id}', [BarangayController::class, 'destroy']); // Delete a barangay by ID
    Route::put('/barangays/{id}/update-status', [BarangayController::class, 'updateStatus'])->name('admin.barangays.update-status');

    // Infant resource routes
    Route::get('/infants', [InfantController::class, 'index']); // Get all infants
    Route::get('/infants/{id}', [InfantController::class, 'show']); // Get a single infant by ID
    Route::post('/infants', [InfantController::class, 'store']); // Store a new infant
    Route::put('/infants/{id}', [InfantController::class, 'update']); // Update an infant by ID
    Route::delete('/infants/{id}', [InfantController::class, 'destroy']); // Delete an infant by ID
    Route::get('/getFilteredInfants/{barangay_id}/{year?}', [InfantController::class, 'getFilteredInfants']);
    Route::get('/infants/{id}/immunization-history', [InfantController::class, 'viewImmunizationHistory']);

    // Vaccine resource routes
    Route::get('/vaccines', [VaccineController::class, 'index']); // Get all vaccines
    Route::get('/vaccines/{id}', [VaccineController::class, 'show']); // Get a single vaccine by ID
    Route::post('/vaccines', [VaccineController::class, 'store']); // Create a new vaccine
    Route::put('/vaccines/{id}', [VaccineController::class, 'update']); // Update a vaccine by ID
    Route::delete('/vaccines/{id}', [VaccineController::class, 'destroy']); // Delete a vaccine by ID
    Route::put('/vaccines/{id}/update-status', [VaccineController::class, 'updateStatus']);
    
    // Vaccine Doses resource routes
    Route::get('/vaccine-doses', [VaccineDoseController::class, 'index']); // Get all vaccine doses
    Route::get('/vaccine-doses/{id}', [VaccineDoseController::class, 'show']); // Get a single vaccine dose by ID
    Route::post('/vaccine-doses', [VaccineDoseController::class, 'store']); // Store a new vaccine dose
    Route::put('/vaccine-doses/{id}', [VaccineDoseController::class, 'update']); // Update a vaccine dose by ID
    Route::delete('/vaccine-doses/{id}', [VaccineDoseController::class, 'destroy']); // Delete a vaccine dose by ID

    // Immunization Record resource routes
    Route::get('/immunization-records', [ImmunizationRecordController::class, 'index']);
    Route::get('/immunization-records/{id}', [ImmunizationRecordController::class, 'show']);
    Route::post('/immunization-records', [ImmunizationRecordController::class, 'store']);
    Route::put('/immunization-records/{id}', [ImmunizationRecordController::class, 'update']);
    Route::delete('/immunization-records/{id}', [ImmunizationRecordController::class, 'destroy']);

});

// Logout route
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);

