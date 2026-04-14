<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('manasik_hajj_users', function (Blueprint $table) {
            $table->unsignedInteger('manasik_guide_completions_count')->default(0)->after('preferences');
        });

        Schema::create('manasik_global_stats', function (Blueprint $table) {
            $table->unsignedTinyInteger('id')->primary();
            $table->unsignedBigInteger('guide_full_completions')->default(0);
            $table->timestamps();
        });

        DB::table('manasik_global_stats')->insert([
            'id'                       => 1,
            'guide_full_completions'   => 0,
            'created_at'               => now(),
            'updated_at'               => now(),
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('manasik_global_stats');

        Schema::table('manasik_hajj_users', function (Blueprint $table) {
            $table->dropColumn('manasik_guide_completions_count');
        });
    }
};
