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
        return view('admin.login');
    }

    public function login(Request $request)
{
    $request->validate([
        'login' => 'required|string',
        'password' => 'required|string',
    ]);

    $user = User::where('email', $request->login)
        ->orWhere('username', $request->login)
        ->first();

    if (! $user || ! Hash::check($request->password, $user->password)) {
        throw ValidationException::withMessages([
            'login' => ['Credenziali non valide.'],
        ]);
    }

    if (! $user->hasVerifiedEmail()) {
        return back()->withErrors(['login' => 'Verifica prima la tua email.']);
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