<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class QuestionSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();
        $questions = [];

        for ($i = 1; $i <= 80; $i++) {
            $questions[] = [
                'question' => "Domanda {$i}: [placeholder del testo della domanda]",
                'option_a' => "Opzione A per domanda {$i}",
                'option_b' => "Opzione B per domanda {$i}",
                'option_c' => "Opzione C per domanda {$i}",
                'option_d' => "Opzione D per domanda {$i}",
                'correct_option' => 'a',
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        DB::table('questions')->insert($questions);
    }
}