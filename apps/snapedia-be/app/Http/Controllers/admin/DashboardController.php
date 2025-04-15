<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Article;
use App\Models\WriterTest;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function index()
    {
        return view('admin.dashboard', [
            'totalUsers' => User::count(),
            'activeUsers' => User::whereNotNull('last_login_ip')->count(),
            'snapReaders' => User::where('type', 'user')->count(),
            'snapWriters' => User::where('type', 'writer')->count(),

            // Articoli scritti da SnapWriters
            'writerArticles' => Article::whereHas('author', function ($q) {
                $q->where('type', 'writer');
            })->count(),

            // Articoli salvati da Wikipedia (autore con username "Wikipedia")
            'storedWikipedia' => Article::whereHas('author', function ($q) {
                $q->where('username', 'Wikipedia');
            })->count(),

            // 'writersReadyForPayout' => User::where('type', 'writer')
            //     ->where('eligible_for_payout', true)
            //     ->get(),

            'tests' => WriterTest::with('user')->latest()->get(),
        ]);
    }
}

