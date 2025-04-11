<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/** MIGRATION: FOLLOWERS */
class CreateFollowersTable extends Migration {
    public function up() {
        Schema::create('followers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('follower_id')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
        });
    }
}
