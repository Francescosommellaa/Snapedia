<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/** MIGRATION: WRITER TESTS */
class CreateWriterTestsTable extends Migration {
    public function up() {
        Schema::create('writer_tests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->enum('status', ['pending', 'passed', 'failed'])->default('pending');
            $table->integer('score')->nullable();
            $table->timestamp('submitted_at')->nullable();
            $table->timestamps();
        });
    }
}
