<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('shop_theme_customizations')) {
            return;
        }

        DB::table('shop_theme_customizations')
            ->whereIn('type', [
                'footer_links',
                'services_content',
                'product_carousel',
                'portal_footer',
            ])
            ->delete();
    }

    public function down(): void
    {
        // Irreversible cleanup migration.
    }
};

