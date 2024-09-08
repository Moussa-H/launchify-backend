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
        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('startup_id');
            $table->integer('office_rent')->default(0);
            $table->integer('marketing')->default(0);
            $table->integer('legal_accounting')->default(0);
            $table->integer('maintenance')->default(0);
            $table->integer('software_licenses')->default(0);
            $table->integer('office_supplies')->default(0);
            $table->integer('miscellaneous')->default(0);
            $table->year('year');
            $table->tinyInteger('month');
            $table->timestamps();

            $table->unique(['startup_id', 'year', 'month'], 'unique_expense_date');

            $table->foreign('startup_id')->references('id')->on('startups')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expenses');
    }
};
