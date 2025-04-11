<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/** MIGRATION: ARTICLES */
class CreateArticlesTable extends Migration {
    public function up() {
        Schema::create('articles', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('short_text');
            $table->longText('long_text');
            $table->string('image_vertical');
            $table->string('image_horizontal')->nullable();
            $table->enum('type', ['snapwriter', 'wikipedia_stored', 'wikipedia_live']);
            $table->foreignId('author_id')->nullable()->constrained('users')->nullOnDelete();
            $table->integer('likes_count')->default(0);
            $table->integer('comments_count')->default(0);
            $table->integer('saves_count')->default(0);
            $table->timestamp('published_at')->nullable();
            $table->decimal('lat', 10, 7)->nullable();
            $table->decimal('lng', 10, 7)->nullable();
            $table->timestamps();
        });
    }
}
