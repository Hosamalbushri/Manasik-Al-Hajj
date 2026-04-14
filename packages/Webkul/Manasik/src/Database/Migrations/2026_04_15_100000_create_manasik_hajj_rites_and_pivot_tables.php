<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('manasik_hajj_rites', function (Blueprint $table) {
            $table->id();
            $table->string('slug', 128)->unique();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('status')->default(true);
            $table->json('content')->nullable()->comment('default_locale + translations: tab_label, title, subtitle, badge, description, info_items');
            $table->timestamps();
        });

        Schema::create('manasik_hajj_rite_dua', function (Blueprint $table) {
            $table->id();
            $table->foreignId('manasik_hajj_rite_id')->constrained('manasik_hajj_rites')->cascadeOnDelete();
            $table->foreignId('manasik_dua_id')->constrained('manasik_duas')->cascadeOnDelete();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();

            $table->unique(['manasik_hajj_rite_id', 'manasik_dua_id'], 'manasik_hajj_rite_dua_unique_pair');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('manasik_hajj_rite_dua');
        Schema::dropIfExists('manasik_hajj_rites');
    }
};
