<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WriterTest;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class WriterTestAdminController extends Controller
{
    /** GET /admin/writer-tests */
    public function index()
    {
        $tests = WriterTest::with('user')
            ->orderByDesc('submitted_at')
            ->get()
            ->groupBy('user_id');

        $summary = $tests->map(function ($group) {
            $latest = $group->first();
            return [
                'user' => $latest->user,
                'attempts' => $group->count(),
                'latest_score' => $latest->score,
                'latest_status' => $latest->status,
                'latest_date' => $latest->submitted_at,
                'pdf_path' => $latest->pdf_path,
            ];
        })->values();

        return view('admin.writer-tests.index', ['summaries' => $summary]);
    }

    /** GET /admin/writer-tests/{userId}/pdf */
    public function showPdf($userId)
    {
        $test = WriterTest::where('user_id', $userId)
            ->orderByDesc('submitted_at')
            ->firstOrFail();

        if (!Storage::disk('public')->exists($test->pdf_path)) {
            abort(404, 'PDF non trovato');
        }

        $content = Storage::disk('public')->get($test->pdf_path);
        return response($content, 200)->header('Content-Type', 'application/pdf');
    }
}