<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\PremiumHistory;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class UserController extends Controller
{
    /** GET /user/me */
    public function me()
    {
        return response()->json(Auth::user()->load('premiumTier'));
    }

    /** GET /users/{id} */
    public function show($id)
    {
        $user = User::withCount(['articles', 'followers', 'likes'])
            ->findOrFail($id);

        $earned = Article::where('author_id', $user->id)
            ->where('likes_count', '>=', 25)
            ->count() * 2;

        return response()->json([
            'user' => $user,
            'guadagni_stimati' => $earned
        ]);
    }

    /** PUT /user/update */
    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'sometimes|string|max:255',
            'username' => 'sometimes|string|max:50|unique:users,username,' . $user->id,
            'email' => 'sometimes|email|unique:users,email,' . $user->id,
            'city' => 'sometimes|string|max:100',
            'bio' => 'sometimes|string|max:500',
            'profile_image' => 'nullable|image|mimes:jpeg,png|max:2048'
        ]);

        // Check modifiche email/username
        if ($request->hasAny(['username', 'email'])) {
            $log = DB::table('user_update_logs')
                ->where('user_id', $user->id)
                ->whereBetween('created_at', [Carbon::now()->subDays(30), now()])
                ->count();

            if ($log >= 2) {
                return response()->json(['error' => 'Puoi modificare email o username solo 2 volte ogni 30 giorni.'], 403);
            }

            DB::table('user_update_logs')->insert([
                'user_id' => $user->id,
                'field' => $request->has('username') ? 'username' : 'email',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        if ($request->hasFile('profile_image')) {
            $filename = 'user_' . $user->id . '_' . time() . '.' . $request->file('profile_image')->extension();
            $path = $request->file('profile_image')->storeAs('profile-picture', $filename, 'public');
            $user->profile_image = $path;
        }

        $user->fill($request->only(['name', 'username', 'email', 'city', 'bio']))->save();

        return response()->json(['message' => 'Profilo aggiornato', 'user' => $user]);
    }

    /** GET /users/search?q=... */
    public function search(Request $request)
    {
        $q = $request->query('q');
        $results = User::where('name', 'ilike', "%{$q}%")
            ->orWhere('username', 'ilike', "%{$q}%")
            ->get();

        return response()->json($results);
    }

    /** DELETE /user */
    public function destroy(Request $request)
    {
        $user = Auth::user();

        $latest = PremiumHistory::where('user_id', $user->id)
            ->whereNull('cancelled_at')
            ->latest('started_at')
            ->first();

        if ($latest && $latest->plan_type === 'yearly_commitment') {
            $daysLeft = now()->diffInDays(Carbon::parse($latest->expires_at));
            $monthlyRate = $latest->amount / 12;
            $debt = round($monthlyRate * ($daysLeft / 30) * 0.35, 2);

            return response()->json([
                'message' => 'Hai ancora un debito da saldare prima di poter eliminare il tuo account.',
                'amount_due' => $debt,
                'redirect' => '/premium/checkout/delete-confirmation'
            ], 403);
        }

        $user->delete();
        return response()->json(['message' => 'Account eliminato.']);
    }
}