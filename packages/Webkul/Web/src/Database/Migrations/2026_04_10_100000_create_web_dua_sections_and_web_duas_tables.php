<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('web_dua_sections', function (Blueprint $table) {
            $table->id();
            $table->string('slug', 64)->unique();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('status')->default(true);
            $table->json('content')->nullable()->comment('default_locale + translations: title per locale');
            $table->timestamps();
        });

        Schema::create('web_duas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('web_dua_section_id')->constrained('web_dua_sections')->cascadeOnDelete();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('status')->default(true);
            $table->json('content')->nullable()->comment('default_locale + translations: title, text, reference');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('web_duas');
        Schema::dropIfExists('web_dua_sections');
    }
};
