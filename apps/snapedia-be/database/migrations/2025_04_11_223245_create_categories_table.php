<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/** MIGRATION: CATEGORIES */
class CreateCategoriesTable extends Migration {
    public function up() {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('wikipedia_id')->nullable();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('lang', 5)->default('it');
            $table->timestamps();
        });
    }
}
