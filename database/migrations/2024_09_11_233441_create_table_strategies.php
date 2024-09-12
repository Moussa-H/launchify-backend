<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('strategies', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('startup_id');
            $table->string('title');
            $table->text('description');
            $table->text('actionable_steps');
            $table->text('potential_challenges');
            $table->timestamps();

            $table->foreign('startup_id')->references('id')->on('startups')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('strategies');
    }
};
