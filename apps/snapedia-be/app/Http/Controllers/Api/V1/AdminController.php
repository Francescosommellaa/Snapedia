<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Article;
use App\Models\WriterTest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        $totalUsers = User::count();
        $activeUsers = User::whereNotNull('last_login_ip')->count();

        $snapReaders = User::whereHas('premiumTier', fn($q) => $q->where('name', 'snapreader'))->count();
        $snapWriters = User::whereHas('premiumTier', fn($q) => $q->where('name', 'snapwriter'))->count();

        $writerArticles = Article::where('type', 'snapwriter')->count();
        $storedWikipedia = Article::where('type', 'wikipedia_stored')->count();

        $writersReadyForPayout = User::whereHas('premiumTier', fn($q) => $q->where('name', 'snapwriter'))
            ->withCount(['articles', 'followers', 'likes'])
            ->get()
            ->filter(fn($u) => $u->followers_count >= 200 && $u->articles_count >= 20 && $u->likes_count >= 100);

        $tests = WriterTest::with('user')->get();

        return view('admin.dashboard', compact(
            'totalUsers', 'activeUsers', 'snapReaders', 'snapWriters',
            'writerArticles', 'storedWikipedia', 'writersReadyForPayout', 'tests'
        ));
    }

    public function approveWriter(Request $request, $id)
    {
        $test = WriterTest::findOrFail($id);
        $user = $test->user;
        $user->is_verified = true;
        $user->save();

        // Notifica email
        Mail::to('snapwriters@snapedia.app')->send(new \App\Mail\WriterApproved($user));

        return back()->with('success', 'SnapWriter approvato!');
    }

    public function rejectWriter(Request $request, $id)
    {
        $test = WriterTest::findOrFail($id);
        $user = $test->user;
        $user->is_verified = false;
        $user->save();

        return back()->with('info', 'SnapWriter rifiutato.');
    }
}
