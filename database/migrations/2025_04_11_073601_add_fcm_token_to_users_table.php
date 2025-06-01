<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'google2fa_secret')) {
                $table->string('google2fa_secret')->nullable()->after('status');
            }
            if (!Schema::hasColumn('users', 'has_2fa_enabled')) {
                $table->boolean('has_2fa_enabled')->default(false)->after('google2fa_secret');
            }
            $table->string('fcm_token')->nullable()->after('has_2fa_enabled');
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('fcm_token');
        });
    }
};
