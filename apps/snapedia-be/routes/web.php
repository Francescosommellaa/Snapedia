<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminRegistrationController;
use App\Http\Controllers\Admin\AdminLoginController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\AdminForgotPasswordController;

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
Route::post('/admin/auth/register-redirect', [AdminRegistrationController::class, 'redirectToRegister'])->name('admin.auth.register.redirect');


/*
|--------------------------------------------------------------------------
| Registrazione multi-step Admin (accessibile solo da IP autorizzato)
| Prefix: /admin/auth/register
|--------------------------------------------------------------------------
*/
Route::prefix('admin/auth/register')->name('admin.auth.register.')->group(function () {
    // 👤 Step 1: inserimento email
    Route::get('/', [AdminRegistrationController::class, 'showForm'])->name('show');

    // 📧 Invio OTP alla mail
    Route::post('/email', [AdminRegistrationController::class, 'submitEmail'])->name('email');

    // ✅ Verifica codice OTP ricevuto
    Route::post('/verify', [AdminRegistrationController::class, 'verifyOtp'])->name('verify');

    // 🔒 Step finale: crea account
    Route::post('/finalize', [AdminRegistrationController::class, 'finalize'])->name('finalize');
});


/*
|--------------------------------------------------------------------------
| Login Admin + Logout
|--------------------------------------------------------------------------
*/

// 🔑 Mostra form login
Route::get('/admin/auth/login', [AdminLoginController::class, 'showLoginForm'])->name('admin.auth.login.form');

// 🔐 Esegui login
Route::post('/admin/auth/login', [AdminLoginController::class, 'login'])->name('admin.auth.login.submit');

// 🚪 Logout
Route::post('/admin/auth/logout', [AdminLoginController::class, 'logout'])->name('admin.auth.logout');


/*
|--------------------------------------------------------------------------
| Recupero password admin (multi-step)
|--------------------------------------------------------------------------
*/

Route::get('/admin/auth/recover-password', [AdminForgotPasswordController::class, 'showForm'])->name('admin.auth.recover.form');
Route::post('/admin/auth/recover-password', [AdminForgotPasswordController::class, 'handleStep'])->name('admin.auth.recover.handle');


/*
|--------------------------------------------------------------------------
| Dashboard Admin (protetta da auth + is_admin)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
});
