<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Cache;
use App\Mail\AdminOtpMail;
use App\Models\User;

class AdminForgotPasswordController extends Controller
{
    public function showForm()
    {
        return view('admin.auth.recover', ['step' => 1]);
    }

    public function handleStep(Request $request)
    {
        $step = $request->input('step');

        if ($step == 1) {
            $request->validate(['email' => 'required|email']);

            $user = User::where('email', $request->email)->where('type', 'admin')->first();

            if (! $user) {
                return back()->withErrors(['email' => 'Nessun account admin trovato con questa email.'])->withInput()->with('step', 1);
            }

            $otp = rand(100000, 999999);
            Cache::put("admin_reset_otp:{$user->email}", $otp, now()->addMinutes(10));

            Mail::to($user->email)->send(new AdminOtpMail($otp));

            return back()->with(['email' => $user->email, 'step' => 2]);
        }

        if ($step == 2) {
            $request->validate([
                'email' => 'required|email',
                'code' => 'required|digits:6',
            ]);

            $cached = Cache::get("admin_reset_otp:{$request->email}");

            if (! $cached || $cached != $request->code) {
                return back()->withErrors(['code' => 'Codice errato o scaduto.'])->withInput()->with(['email' => $request->email, 'step' => 2]);
            }

            return back()->with(['email' => $request->email, 'step' => 3]);
        }

        if ($step == 3) {
          try {
              $validated = $request->validate([
                  'email' => 'required|email',
                  'password' => ['required', 'confirmed', 'min:8', 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/'],
              ],
              [
                  'password.confirmed' => 'Le due password non coincidono.',
                  'password.min' => 'La password deve contenere almeno 8 caratteri.',
                  'password.regex' => 'La password deve contenere almeno una lettera maiuscola, una minuscola e un numero.',
              ]);
          } catch (\Illuminate\Validation\ValidationException $e) {
              return back()
                  ->withErrors($e->validator)
                  ->withInput()
                  ->with([
                      'step' => 3,
                      'email' => $request->email,
                  ]);
          }
      
          $user = User::where('email', $request->email)->where('type', 'admin')->firstOrFail();
      
          if (Hash::check($request->password, $user->password)) {
              return back()->withErrors([
                  'password' => 'La nuova password non può essere uguale a quella precedente.',
              ])->withInput()->with([
                  'step' => 3,
                  'email' => $request->email,
              ]);
          }
      
          $user->password = Hash::make($request->password);
          $user->save();
      
          Cache::forget("admin_reset_otp:{$request->email}");
      
          return redirect()->route('admin.auth.login.form')->with('success', 'Password aggiornata. Ora puoi accedere.');
      }
    }
}
