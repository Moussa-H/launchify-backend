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
        Schema::create('startup_investment_sources', function (Blueprint $table) {
            $table->id();
            $table->foreignId('startup_id')->constrained('startups')->onDelete('cascade');
            $table->enum('investment_source', ['Business Angel', 'Public grant', 'Accelerator', 'Corporate', 'VC Fund', 'Crowd']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('startup_investment_sources');
    }
};
