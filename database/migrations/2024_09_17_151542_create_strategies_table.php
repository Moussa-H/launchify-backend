
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

            // Strategy 1
            $table->string('strategy_1_name')->nullable();
            $table->text('strategy_1_description')->nullable();
            $table->enum('strategy_1_status', ['todo', 'in progress', 'completed'])->default('todo');

            // Strategy 2
            $table->string('strategy_2_name')->nullable();
            $table->text('strategy_2_description')->nullable();
            $table->enum('strategy_2_status', ['todo', 'in progress', 'completed'])->default('todo');

            // Strategy 3
            $table->string('strategy_3_name')->nullable();
            $table->text('strategy_3_description')->nullable();
            $table->enum('strategy_3_status', ['todo', 'in progress', 'completed'])->default('todo');

            // Strategy 4
            $table->string('strategy_4_name')->nullable();
            $table->text('strategy_4_description')->nullable();
            $table->enum('strategy_4_status', ['todo', 'in progress', 'completed'])->default('todo');

            // Strategy 5
            $table->string('strategy_5_name')->nullable();
            $table->text('strategy_5_description')->nullable();
            $table->enum('strategy_5_status', ['todo', 'in progress', 'completed'])->default('todo');

            $table->timestamps();

            // Foreign key constraint
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
