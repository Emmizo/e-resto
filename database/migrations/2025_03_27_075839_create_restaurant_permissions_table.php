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
        Schema::create('restaurant_permissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('restaurant_id')->constrained('restaurants')->onDelete('cascade');

            // Consider adding a status or granularity column
            $table->string('permission_name');
            $table->boolean('is_active')->default(true); // Optional: for more granular control

            $table->timestamps();

            // Unique constraint remains the same
            $table->unique(['user_id', 'restaurant_id', 'permission_name'], 'unq_rest_perm');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('restaurant_permissions');
    }
};
