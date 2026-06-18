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
        Schema::table('menu_items', function (Blueprint $table) {
            $table->unsignedInteger('stock_quantity')->nullable()->after('is_available')
                  ->comment('null = unlimited');
            $table->unsignedInteger('total_sold')->default(0)->after('stock_quantity');
            $table->boolean('track_inventory')->default(false)->after('total_sold');
        });
    }

    public function down(): void
    {
        Schema::table('menu_items', function (Blueprint $table) {
            $table->dropColumn(['stock_quantity', 'total_sold', 'track_inventory']);
        });
    }
};
