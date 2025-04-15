<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
{
    $this->call([
        PremiumTiersSeeder::class,
        CategoriesSeeder::class,
        QuestionSeeder::class,
    ]);

    User::firstOrCreate(
        ['email' => 'wikipedia@system.local'],
        [
            'name' => 'Wikipedia',
            'surname' => 'Bot',
            'username' => 'Wikipedia',
            'password' => bcrypt('wikipedia_secure'), 
            'type' => 'user',
            'age' => 100,
            'is_verified' => true,
            'email_verified_at' => now(),
            'premium_tier_id' => \App\Models\PremiumTier::where('name', 'SnapWriter')->value('id')
        ]
    );
}
}
