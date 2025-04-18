<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\PremiumTier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;
use Illuminate\Auth\Events\Registered;
use App\Mail\AdminOtpMail;

class AdminRegistrationController extends Controller
{
    // 🏠 Pagina iniziale
    public function welcome()
    {
        return view('admin.welcome');
    }

    // 🔐 Redirect alla registrazione se IP è autorizzato
    public function redirectToRegister(Request $request)
    {
        if ($request->ip() !== env('ADMIN_CREATOR_IP')) {
            return redirect('/admin')->with('error', 'Chiedi all\'admin principale di registrarti.');
        }

        return redirect('/admin/auth/register');
    }

    // 🧾 Step 1: mostra form inserimento email
    public function showForm(Request $request)
    {
        if ($request->ip() !== env('ADMIN_CREATOR_IP')) {
            abort(403, 'Accesso non autorizzato');
        }

        return view('admin.auth.register');
    }

    // 📧 Invia OTP alla mail, con protezione 30 secondi
    public function submitEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);
    
        $email = strtolower(trim($request->email));
    
        // 🔁 Se l'email è già registrata, manda al login
        if (User::where('email', $email)->exists()) {
            return redirect()->route('admin.auth.login.form')->with('info', 'Email già registrata. Effettua il login.');
        }
    
        // ✅ Pulisci OTP e cooldown precedenti (in caso di email cambiata o ritorno a step 1)
        if (!Cache::has("admin_otp:{$email}")) {
            Cache::forget("otp_last_sent:{$email}");
            Cache::forget("admin_verified:{$email}");
        }
    
        // ✅ Salva un nuovo OTP
        $otp = rand(100000, 999999);
        Cache::put("admin_otp:{$email}", $otp, now()->addMinutes(10));
        Cache::put("otp_last_sent:{$email}", now());
    
        Mail::to($email)->send(new AdminOtpMail($otp));
    
        return back()->with([
            'email' => $email,
            'step' => 2,
            'resent' => true,
            'cooldown' => 30,
        ]);
    }

    // ✅ Step 2: verifica codice OTP
    public function verifyOtp(Request $request)
    {
        $email = strtolower(trim($request->email));
    
        // 🔁 Se ha cliccato "Invia nuovo codice"
        if ($request->input('action') === 'resend') {
            return redirect()->route('admin.auth.register.email')->withInput(['email' => $email]);
        }
    
        // ✅ Se ha cliccato "Verifica codice", procedi alla validazione
        $request->validate([
            'email' => 'required|email',
            'code' => 'required|digits:6',
        ]);
    
        $code = Cache::get("admin_otp:{$email}");
    
        if (!hash_equals((string) $code, (string) $request->code)) {
            return back()->withErrors(['code' => 'Codice errato o scaduto'])>with([
                'email' => $email,
                'step' => 2,
            ]);
        }
    
        // ✅ Codice corretto: marca l'email come verificata temporaneamente
        Cache::put("admin_verified:{$email}", true, now()->addMinutes(15));
    
        return back()->with([
            'email' => $email,
            'step' => 3,
        ]);
    }

    // 👤 Step 3: finalizzazione creazione account admin
    public function finalize(Request $request)
    {
        $email = strtolower(trim($request->email));

        if (!Cache::get("admin_verified:{$email}")) {
            return back()->withErrors(['email' => 'Email non verificata.']);
        }

        $request->validate([
            'name' => 'required|string|max:100',
            'surname' => 'required|string|max:100',
            'username' => [
                'required', 'alpha_num', 'min:3', 'max:18',
                'not_regex:/[€$@!%^&*()\"\'<>?=+{}\[\]«»¬\\|]/',
                'not_in:wikipedia',
                'unique:users,username'
            ],
            'password' => [
                'required', 'confirmed', 'min:8',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/'
            ],
        ]);

        $admin = User::create([
            'name' => $request->name,
            'surname' => $request->surname,
            'username' => $request->username,
            'email' => $email,
            'password' => Hash::make($request->password),
            'type' => 'admin',
            'age' => 99,
            'premium_tier_id' => PremiumTier::where('name', 'free')->value('id'),
            'is_verified' => true,
        ]);
        event(new Registered($admin));
        $admin->markEmailAsVerified();
        Cache::forget("admin_verified:{$email}");
        Cache::forget("admin_otp:{$email}");

        return redirect()->route('admin.auth.login.form')->with('success', 'Registrazione completata! Ora accedi.');
    }
}
