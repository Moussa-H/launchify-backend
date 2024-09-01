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
            $table->string('company_name');
            $table->text('description');
            $table->string('founder');
            $table->string('industry');
            $table->year('founding_year');
            $table->string('country');
            $table->string('city');
            $table->text('key_challenges')->nullable();
            $table->text('goals')->nullable();
            $table->enum('business_type', ['B2B', 'B2C', 'B2B2C', 'B2G', 'C2C']);
            $table->enum('company_stage', ['Idea', 'Pre-seed', 'Seed', 'Early Growth', 'Growth', 'Maturity']);
            $table->integer('employees_count')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('email_address')->nullable();
            $table->string('website_url')->nullable();
            $table->enum('currently_raising_type', ['Founders', 'Family & Friends', 'Pre-seed', 'Seed', 'Pre-series A', 'Series A'])->nullable();
            $table->decimal('currently_raising_size', 15, 2)->nullable();
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
