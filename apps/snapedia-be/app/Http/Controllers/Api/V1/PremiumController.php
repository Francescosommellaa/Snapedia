<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\PremiumTier;
use App\Models\PremiumHistory;
use App\Models\User;
use App\Models\WriterTest;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use App\Notifications\PremiumRenewalReminder;
use App\Notifications\WriterTestPassedNotification;
use App\Notifications\WriterTestFailedNotification;

class PremiumController extends Controller
{
    /** GET /premium/options */
    public function options()
    {
        return response()->json(PremiumTier::all());
    }

    /** GET /premium/my-plan */
    public function myPlan()
    {
        return response()->json(Auth::user()->load('premiumTier'));
    }

    /** POST /premium/upgrade */
    public function upgrade(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'tier' => 'required|in:snapreader,snapwriter',
            'plan_type' => 'required|in:monthly,yearly,yearly_commitment',
            'payment_method' => 'required|in:stripe,paypal',
        ]);

        $tier = PremiumTier::where('name', $validated['tier'])->firstOrFail();

        if ($validated['tier'] === 'snapwriter') {
            $test = WriterTest::where('user_id', $user->id)->first();
            if (! $test || $test->status !== 'passed') {
                return response()->json(['error' => 'Non puoi diventare SnapWriter finché non superi il test.'], 403);
            }
        }

        PremiumHistory::create([
            'user_id' => $user->id,
            'from_tier_id' => $user->premium_tier_id,
            'to_tier_id' => $tier->id,
            'plan_type' => $validated['plan_type'],
            'started_at' => now(),
            'expires_at' => now()->addMonths($validated['plan_type'] === 'monthly' ? 1 : 12),
            'amount' => $tier->price_monthly * ($validated['plan_type'] === 'monthly' ? 1 : 12),
            'payment_method' => $validated['payment_method'],
        ]);

        $user->premium_tier_id = $tier->id;
        $user->save();

        return response()->json(['message' => 'Piano attivato correttamente.']);
    }

    /** POST /premium/cancel */
    public function cancel()
    {
        $user = Auth::user();

        $latest = PremiumHistory::where('user_id', $user->id)
            ->latest('started_at')->first();

        if ($latest) {
            $latest->cancelled_at = now();
            $latest->save();
        }

        $user->premium_tier_id = PremiumTier::where('name', 'free')->value('id');
        $user->save();

        return response()->json(['message' => 'Abbonamento cancellato. Ora sei in modalità Free.']);
    }

    /** POST /premium/admin-switch */
    public function adminSwitch(Request $request)
    {
        $admin = Auth::user();
        if ($admin->type !== 'admin') abort(403);

        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'tier' => 'required|in:free,snapreader,snapwriter',
            'plan_type' => 'required|in:monthly,yearly,yearly_commitment',
        ]);

        $user = User::findOrFail($validated['user_id']);
        $tier = PremiumTier::where('name', $validated['tier'])->firstOrFail();

        PremiumHistory::create([
            'user_id' => $user->id,
            'from_tier_id' => $user->premium_tier_id,
            'to_tier_id' => $tier->id,
            'plan_type' => $validated['plan_type'],
            'started_at' => now(),
            'expires_at' => now()->addMonths($validated['plan_type'] === 'monthly' ? 1 : 12),
            'amount' => 0,
            'payment_method' => 'admin',
        ]);

        $user->premium_tier_id = $tier->id;
        $user->save();

        return response()->json(['message' => 'Piano aggiornato da admin.']);
    }

    /** GET /premium/history */
    public function history()
    {
        $user = Auth::user();
        return response()->json(PremiumHistory::where('user_id', $user->id)->get());
    }

    /** JOB: invio notifica pre-rinnovo */
    public function notifyRenewal()
    {
        $expiring = PremiumHistory::whereNull('cancelled_at')
            ->whereDate('expires_at', Carbon::now()->addDays(7)->toDateString())
            ->with('user')
            ->get();

        foreach ($expiring as $entry) {
            Notification::send($entry->user, new PremiumRenewalReminder($entry));
        }

        return response()->json(['message' => 'Notifiche inviate con successo.']);
    }

    /** NOTIFICHE TEST SCRITTORI */
    public function notifyTestOutcome(WriterTest $test)
    {
        if ($test->status === 'passed') {
            Notification::send($test->user, new WriterTestPassedNotification());
        } elseif ($test->status === 'failed') {
            Notification::send($test->user, new WriterTestFailedNotification());
        }
    }
}
