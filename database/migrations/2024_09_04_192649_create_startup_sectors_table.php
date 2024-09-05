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
    if (!Schema::hasTable('startup_sectors')) {
        Schema::create('startup_sectors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('startup_id')->constrained('startups')->onDelete('cascade');
            $table->unsignedInteger('sector_id');
            $table->foreign('sector_id')->references('id')->on('sectors');
        });
    }
}

  
    public function down(): void
    {
        Schema::dropIfExists('startup_sectors');
    }
};
