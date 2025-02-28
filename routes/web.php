<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::middleware('guest.custom')->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');

    Route::post('/Login', [LoginController::class, 'login'])->name('login');

    Route::get('/ForgotPassword', [AccountController::class, 'forgotPasswordPage'])->name('forgotPassword');
    Route::post('/ForgotPassword', [AccountController::class, 'ForgotPasswordStore'])->name('ForgotPasswordStore');
    Route::get('/CreateAccount', [AccountController::class, 'createAccountPage'])->name('createAccount');
    Route::post('/CreateAccount', [AccountController::class, 'createAccountStore'])->name('store.createAccount');
});

Route::middleware('logged.custom')->group(function () {
    Route::get('/Dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/CreateContact', [ContactController::class, 'create'])->name('createContact');
    Route::post('/CreateContact', [ContactController::class, 'store'])->name('store.contact');
    Route::get('/editContact/{token}', [ContactController::class, 'edit'])->name('edit.contact');
    Route::post('/updateContact', [ContactController::class, 'update'])->name('update.contact');
    Route::post('/deleteContact', [ContactController::class, 'delete'])->name('delete.contact');

    Route::get('/editAccount', [AccountController::class, 'editAccount'])->name('edit.account');
    Route::post('/deleteAccount', [AccountController::class, 'deleteAccount'])->name('delete.account');
    Route::post('/changePassword', [AccountController::class, 'changePassword'])->name('changePassword.account');

    Route::post('/Logout', [LoginController::class, 'logout'])->name('logout');
});
