<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Models\User;

class AdminLoginController extends Controller
{
    public function showLoginForm()
    {
        return view('admin.auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'login' => 'required|string',
            'password' => 'required|string',
        ]);

        $user = User::where(function ($q) use ($request) {
            $q->where('email', $request->login)
              ->orWhere('username', $request->login);
        })->where('type', 'admin')->first();

        // ❌ Utente non trovato → messaggio + campo login svuotato
        if (! $user) {
            return back()->withErrors([
                'login' => 'Email o username non trovati.',
            ]);
        }

        // ❌ Password errata → messaggio + campo login ripopolato
        if (! Hash::check($request->password, $user->password)) {
            return back()->withErrors([
                'password' => 'Password errata.',
            ])->withInput(['login' => $request->login]);
        }

        // ❌ Email non verificata
        if (! $user->hasVerifiedEmail()) {
            return back()->withErrors([
                'login' => 'Verifica la tua email prima di accedere.',
            ])->withInput(['login' => $request->login]);
        }

        Auth::login($user, $request->has('remember'));

        return redirect()->route('admin.dashboard');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/admin');
    }

    public function dashboard()
    {
        return redirect()->route('admin.dashboard');
    }
}
