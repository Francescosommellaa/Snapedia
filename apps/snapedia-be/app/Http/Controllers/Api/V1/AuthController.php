<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Category;
use App\Models\PremiumTier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Events\Verified;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;

class AuthController extends Controller
{
    /** REGISTRAZIONE UTENTE CLASSICO */
    public function registerUser(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:30|unique:users,username',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'city' => 'required|string|max:100',
            'categories' => 'required|array|min:5',
            'categories.*' => 'exists:categories,id'
        ]);

        $user = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'city' => $request->city,
            'type' => 'user',
            'premium_tier_id' => PremiumTier::where('name', 'free')->value('id'),
        ]);

        $user->categories()->sync($request->categories);
        event(new Registered($user));

        return response()->json(["message" => "Utente registrato. Controlla la mail per verificare l'account."], 201);
    }

    /** REGISTRAZIONE ADMIN (da IP autorizzato) */
    public function registerAdmin(Request $request)
    {
        $authorizedIp = '123.123.123.123'; // Sostituire con il tuo IP
        if ($request->ip() !== $authorizedIp) {
            abort(403, 'Non autorizzato.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:30|unique:users,username',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
        ]);

        $admin = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'type' => 'admin',
        ]);

        event(new Registered($admin));

        return response()->json(['message' => 'Admin creato con successo.'], 201);
    }

    /** LOGIN */
    public function login(Request $request)
    {
        $request->validate([
            'login' => 'required|string',
            'password' => 'required|string'
        ]);

        $user = User::where('email', $request->login)
            ->orWhere('username', $request->login)
            ->first();

        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages(['login' => ['Credenziali non valide.']]);
        }

        if (! $user->hasVerifiedEmail()) {
            return response()->json(['message' => 'Verifica la tua email prima di accedere.'], 403);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'user' => $user->load('premiumTier'),
        ]);
    }

    /** LOGOUT */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Logout effettuato.']);
    }

    /** PROFILO UTENTE */
    public function me(Request $request)
    {
        return response()->json($request->user()->load('premiumTier'));
    }

    /** VERIFICA EMAIL */
    public function verifyEmail(Request $request, $id, $hash)
    {
        $user = User::findOrFail($id);

        if (! hash_equals((string) $hash, sha1($user->getEmailForVerification()))) {
            abort(403);
        }

        if (! $user->hasVerifiedEmail()) {
            $user->markEmailAsVerified();
            event(new Verified($user));
        }

        return redirect('/email-verificata');
    }

    /** REINVIA EMAIL DI VERIFICA */
    public function resendVerificationEmail(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return response()->json(['message' => 'Email già verificata.']);
        }

        $request->user()->sendEmailVerificationNotification();
        return response()->json(['message' => 'Email di verifica inviata.']);
    }
}
