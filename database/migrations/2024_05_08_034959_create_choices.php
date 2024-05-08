<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('choices', function (Blueprint $table) {
            $table->id();
            $table->string('choice');
            $table->unsignedBigInteger('poll_id');
            $table->timestamps();
            $table->foreign('poll_id')->references('id')->on('polls');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('choices');
    }
};