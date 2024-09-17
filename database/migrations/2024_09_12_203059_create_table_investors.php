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
        Schema::create('investors', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('first_name');
            $table->string('last_name');
            $table->text('description')->nullable();
            $table->string('email')->unique();
            $table->string('phone_number')->nullable();
            $table->enum('investment_source', [
                'Business Angel', 
                'Accelerator / Incubator', 
                'VC Fund', 
                'Corporate', 
                'Public grant', 
                'Crowd'
            ]);
            $table->string('linkedin_url')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('investors');
    }
};
