<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/** MIGRATION: PREMIUM TIERS */
class CreatePremiumTiersTable extends Migration {
    public function up() {
        Schema::create('premium_tiers', function (Blueprint $table) {
            $table->id();
            $table->enum('name', ['free', 'snapreader', 'snapwriter']);
            $table->decimal('price_monthly', 6, 2);
            $table->boolean('can_post');
            $table->boolean('has_ads');
            $table->string('badge')->nullable();
            $table->timestamps();
        });
    }
}
