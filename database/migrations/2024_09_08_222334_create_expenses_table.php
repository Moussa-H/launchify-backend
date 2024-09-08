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
            $table->integer('office_rent')->default(0); // Amount for office rent
            $table->integer('marketing')->default(0); // Amount for marketing
            $table->integer('legal_accounting')->default(0); // Amount for legal & accounting
            $table->integer('maintenance')->default(0); // Amount for maintenance
            $table->integer('software_licenses')->default(0); // Amount for software licenses
            $table->integer('office_supplies')->default(0); // Amount for office supplies
            $table->integer('miscellaneous')->default(0); // Amount for miscellaneous expenses
            $table->timestamps();

            // Foreign key relation with startups
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
