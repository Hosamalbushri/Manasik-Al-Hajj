<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('web_map_locations', function (Blueprint $table) {
            $table->id();
            $table->string('slug', 64)->unique();
            $table->string('map_id', 128);
            $table->string('image')->nullable()->comment('Public storage path or absolute URL');
            $table->text('embed');
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('status')->default(true);
            $table->json('content')->nullable()->comment('default_locale + translations per locale');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('web_map_locations');
    }
};
