<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('team_members', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('startup_id');
            $table->string('fullname');
            $table->string('position');
            $table->integer('salary');
            $table->timestamps();

            $table->foreign('startup_id')->references('id')->on('startups')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('team_members');
    }
};
