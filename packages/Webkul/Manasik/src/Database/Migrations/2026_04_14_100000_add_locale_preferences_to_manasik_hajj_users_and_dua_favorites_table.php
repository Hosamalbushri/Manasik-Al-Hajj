<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('manasik_hajj_users', function (Blueprint $table) {
            $table->string('locale', 20)->nullable()->after('status');
            $table->json('preferences')->nullable()->after('locale');
        });

        Schema::create('manasik_hajj_user_dua_favorites', function (Blueprint $table) {
            $table->id();
            $table->foreignId('manasik_hajj_user_id')->constrained('manasik_hajj_users')->cascadeOnDelete();
            $table->foreignId('manasik_dua_id')->constrained('manasik_duas')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['manasik_hajj_user_id', 'manasik_dua_id'], 'manasik_hajj_user_dua_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('manasik_hajj_user_dua_favorites');

        Schema::table('manasik_hajj_users', function (Blueprint $table) {
            $table->dropColumn(['locale', 'preferences']);
        });
    }
};
