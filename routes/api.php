<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\NominatimController;
use App\Http\Controllers\ViaCepController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
    Route::get('/searchAddress', [ViaCepController::class, 'searchEndereco']);
    Route::get('/searchCep', [ViaCepController::class, 'searchCep']);
    Route::get('/contact-map/{id}', [DashboardController::class, 'getContactMap']);
