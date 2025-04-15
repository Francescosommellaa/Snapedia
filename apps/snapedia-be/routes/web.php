<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminRegistrationController;
use App\Http\Controllers\Admin\AdminLoginController;
use App\Http\Controllers\Admin\DashboardController;

/*
|--------------------------------------------------------------------------
| Redirect Home → /admin
|--------------------------------------------------------------------------
*/
Route::get('/', fn () => redirect('/admin'));


/*
|--------------------------------------------------------------------------
| Area pubblica /admin → schermata benvenuto + registrazione (protetta da IP)
|--------------------------------------------------------------------------
*/

// 🏠 Schermata iniziale area admin
Route::get('/admin', [AdminRegistrationController::class, 'welcome']);

// 🔐 Verifica IP per autorizzare registrazione admin
Route::post('/admin/register-redirect', [AdminRegistrationController::class, 'redirectToRegister']);


/*
|--------------------------------------------------------------------------
| Registrazione multi-step Admin (accessibile solo da IP autorizzato)
| Prefix: /admin/register
|--------------------------------------------------------------------------
*/
Route::prefix('admin/register')->group(function () {
    // 👤 Step 1: inserimento email
    Route::get('/', [AdminRegistrationController::class, 'showForm']);
    
    // 📧 Invio OTP alla mail
    Route::post('/email', [AdminRegistrationController::class, 'submitEmail']);
    
    // ✅ Verifica codice OTP ricevuto
    Route::post('/verify', [AdminRegistrationController::class, 'verifyOtp']);
    
    // 🔒 Step finale: crea account
    Route::post('/finalize', [AdminRegistrationController::class, 'finalize']);
});


/*
|--------------------------------------------------------------------------
| Login Admin + Logout
|--------------------------------------------------------------------------
*/

// 🔑 Mostra form login
Route::get('/admin/login', [AdminLoginController::class, 'showLoginForm'])->name('admin.login.form');

// 🔐 Esegui login
Route::post('/admin/login', [AdminLoginController::class, 'login'])->name('admin.login.submit');

// 🚪 Logout
Route::post('/admin/logout', [AdminLoginController::class, 'logout'])->name('admin.logout');


/*
|--------------------------------------------------------------------------
| Dashboard Admin (protetta da auth + is_admin)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
});