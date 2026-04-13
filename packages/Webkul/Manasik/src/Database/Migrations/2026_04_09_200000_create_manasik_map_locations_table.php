<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('manasik_map_locations', function (Blueprint $table) {
            $table->id();
            $table->string('slug', 64)->unique();
            $table->string('map_id', 128);
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 11, 7)->nullable();
            $table->unsignedTinyInteger('zoom')->nullable()->default(15);
            $table->string('image')->nullable()->comment('Public storage path or absolute URL');
            $table->text('embed')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('status')->default(true);
            $table->json('content')->nullable()->comment('default_locale + translations per locale');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('manasik_map_locations');
    }
};
