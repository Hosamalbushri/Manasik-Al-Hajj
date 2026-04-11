<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('web_map_locations', function (Blueprint $table) {
            $table->decimal('latitude', 10, 7)->nullable()->after('map_id');
            $table->decimal('longitude', 11, 7)->nullable()->after('latitude');
            $table->unsignedTinyInteger('zoom')->nullable()->default(15)->after('longitude');
        });

        Schema::table('web_map_locations', function (Blueprint $table) {
            $table->text('embed')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('web_map_locations', function (Blueprint $table) {
            $table->dropColumn(['latitude', 'longitude', 'zoom']);
        });
    }
};
