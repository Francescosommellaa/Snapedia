<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\WriterTest;
use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use PDF;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class WriterTestController extends Controller
{
    /** GET /writer-test/status */
    public function status()
    {
        $user = Auth::user();
        $lastTest = WriterTest::where('user_id', $user->id)
            ->latest('submitted_at')
            ->first();

        $weeklyAttempts = WriterTest::where('user_id', $user->id)
            ->where('submitted_at', '>=', Carbon::now()->startOfWeek())
            ->count();

        return response()->json([
            'status' => $lastTest?->status,
            'score' => $lastTest?->score,
            'attempts_left' => max(0, 5 - $weeklyAttempts)
        ]);
    }

    /** POST /writer-test/submit */
    public function submit(Request $request)
    {
        $user = Auth::user();
        if ($user->type === 'snapwriter') {
            return response()->json(['error' => 'Sei già SnapWriter.'], 403);
        }

        $attempts = WriterTest::where('user_id', $user->id)
            ->where('submitted_at', '>=', Carbon::now()->startOfWeek())
            ->count();

        if ($attempts >= 5) {
            return response()->json(['error' => 'Hai superato il numero massimo di tentativi settimanali.'], 403);
        }

        $answers = $request->validate([
            'answers' => 'required|array|size:20',
            'answers.*.question_id' => 'required|exists:questions,id',
            'answers.*.selected_option' => 'required|string'
        ]);

        $score = 0;
        $correctAnswers = [];

        foreach ($answers['answers'] as $item) {
            $question = Question::find($item['question_id']);
            $correctAnswers[] = [
                'question' => $question->question,
                'selected' => $item['selected_option'],
                'correct' => $question->correct_option
            ];
            if ($item['selected_option'] === $question->correct_option) {
                $score++;
            }
        }

        $percent = round(($score / 20) * 100);
        $status = $percent >= 70 ? 'passed' : 'failed';

        $test = WriterTest::create([
            'user_id' => $user->id,
            'status' => $status,
            'score' => $percent,
            'submitted_at' => now()
        ]);

        // Generazione PDF
        $pdf = PDF::loadView('pdf.test_result', [
            'user' => $user,
            'score' => $percent,
            'status' => $status,
            'answers' => $correctAnswers
        ]);

        $fileName = 'writer_test_' . $user->id . '_' . time() . '.pdf';
        $path = 'tests/' . $fileName;
        Storage::disk('public')->put($path, $pdf->output());
        $test->pdf_path = $path;
        $test->save();

        // Invio email
        Mail::raw('Test SnapWriter allegato.', function ($msg) use ($pdf) {
            $msg->to('snapwriters@snapedia.app')
                ->subject('Nuovo test SnapWriter')
                ->attachData($pdf->output(), 'test_result.pdf');
        });

        return response()->json([
            'message' => 'Test completato',
            'status' => $status,
            'score' => $percent
        ]);
    }

    /** POST /writer-test/{id}/reject */
    public function reject($id)
    {
        $test = WriterTest::findOrFail($id);
        $test->status = 'failed';
        $test->save();

        return response()->json(['message' => 'Test segnato come fallito.']);
    }

    /** GET /writer-test/questions */
    public function getQuestions()
    {
        $questions = Question::inRandomOrder()->limit(20)->get();
        return response()->json($questions);
    }
}