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
         Schema::create('startups', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('image')->nullable();
            $table->string('company_name')->nullable();
            $table->text('description')->nullable();
            $table->string('founder')->nullable();
            $table->string('industry')->nullable();
            $table->year('founding_year')->nullable();
            $table->string('country')->nullable();
            $table->string('city')->nullable();
            $table->text('key_challenges')->nullable();
            $table->text('goals')->nullable();
            $table->enum('business_type', ['B2B', 'B2C', 'B2B2C', 'B2G', 'C2C'])->nullable();
            $table->enum('company_stage', ['Idea', 'Pre-seed', 'Seed', 'Early Growth', 'Growth', 'Maturity'])->nullable();
            $table->integer('employees_count')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('email_address')->nullable();
            $table->string('website_url')->nullable();
            $table->enum('currently_raising_type', ['Founders', 'Family & Friends', 'Pre-seed', 'Seed', 'Pre-series A', 'Series A','Pre-series B', 'Series B', 'Series C+'])->nullable();
            $table->bigInteger('currently_raising_size')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
       Schema::dropIfExists('startups');
    }
};
