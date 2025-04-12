<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Follower;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use App\Notifications\NewFollowerNotification;

class FollowerController extends Controller
{
    /** TOGGLE FOLLOW/UNFOLLOW */
    public function toggle(User $user)
    {
        $authUser = Auth::user();

        if ($authUser->id === $user->id) {
            return response()->json(['error' => 'Non puoi seguire te stesso.'], 422);
        }

        $isFollowing = Follower::where('user_id', $user->id)
            ->where('follower_id', $authUser->id)
            ->exists();

        if ($isFollowing) {
            Follower::where('user_id', $user->id)
                ->where('follower_id', $authUser->id)
                ->delete();

            $user->decrement('followers_count');
            $authUser->decrement('following_count');

            return response()->json(['message' => 'Unfollow effettuato.']);
        } else {
            Follower::create([
                'user_id' => $user->id,
                'follower_id' => $authUser->id
            ]);

            $user->increment('followers_count');
            $authUser->increment('following_count');

            Notification::send($user, new NewFollowerNotification($authUser));

            return response()->json(['message' => 'Ora stai seguendo ' . $user->name]);
        }
    }

    /** ELENCO CHI SEGUE L'UTENTE */
    public function followers($userId)
    {
        $user = User::findOrFail($userId);

        if ($user->premiumTier && $user->premiumTier->name === 'snapwriter') {
            $followers = Follower::where('user_id', $user->id)
                ->with('follower')
                ->get()
                ->pluck('follower');

            return response()->json($followers);
        }

        return response()->json(['error' => 'I follower sono visibili solo per SnapWriters.'], 403);
    }

    /** ELENCO CHI SEGUE L'AUTENTICATO */
    public function following($userId)
    {
        $user = User::findOrFail($userId);

        $following = Follower::where('follower_id', $user->id)
            ->with('user')
            ->get()
            ->pluck('user');

        return response()->json($following);
    }

    /** CHECK SINGOLO FOLLOW */
    public function isFollowedByMe($userId)
    {
        $me = Auth::id();
        $is = Follower::where('user_id', $userId)
            ->where('follower_id', $me)
            ->exists();

        return response()->json(['is_following' => $is]);
    }
}