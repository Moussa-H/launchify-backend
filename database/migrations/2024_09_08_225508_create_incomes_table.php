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
        Schema::create('incomes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('startup_id');
            $table->integer('product_sales')->default(0); // Amount for product sales
            $table->integer('service_revenue')->default(0); // Amount for service revenue
            $table->integer('subscription_fees')->default(0); // Amount for subscription fees
            $table->integer('investment_income')->default(0); // Amount for investment income
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
        Schema::dropIfExists('incomes');
    }
};
