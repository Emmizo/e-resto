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
        Schema::create('restaurant_employees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('restaurant_id')->constrained()->onDelete('cascade');
            $table->enum('position', ['manager', 'chef', 'waiter', 'cashier', 'other'])->default('other');
            $table->text('permissions')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            // Add indexes for employee filtering
            $table->index('position');
            $table->index('is_active');

            // Create composite index for unique employee-restaurant combinations
            $table->unique(['user_id', 'restaurant_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('restaurant_employees');
    }
};
