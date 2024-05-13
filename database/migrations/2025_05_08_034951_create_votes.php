<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('votes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('choice_id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('poll_id');
            $table->unsignedBigInteger('division_id');
            $table->timestamps();
            $table->foreign('choice_id')->references('id')->on('choices');
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('poll_id')->references('id')->on('polls');
            $table->foreign('division_id')->references('id')->on('divisions');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('votes');
    }
};