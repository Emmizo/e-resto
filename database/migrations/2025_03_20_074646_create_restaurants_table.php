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
        Schema::create('restaurants', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description');
            $table->string('address');
            $table->decimal('longitude', 10, 7);
            $table->decimal('latitude', 10, 7);
            $table->string('phone_number');
            $table->string('email')->nullable();
            $table->string('website')->nullable();
            $table->json('opening_hours');
            $table->string('cuisine_type');
            $table->string('price_range');
            $table->string('image')->nullable();
            $table->foreignId('owner_id')->constrained('users')->onDelete('cascade');
            $table->boolean('is_approved')->default(false);
            $table->timestamps();

            // Add spatial index for location-based queries
            $table->index(['longitude', 'latitude'], 'location_index');

            // Add indexes for common search filters
            $table->index('cuisine_type');
            $table->index('price_range');
            $table->index('is_approved');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('restaurants');
    }
};
