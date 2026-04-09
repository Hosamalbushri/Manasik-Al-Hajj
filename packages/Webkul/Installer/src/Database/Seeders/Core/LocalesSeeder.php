<?php

namespace Webkul\Installer\Database\Seeders\Core;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class LocalesSeeder extends Seeder
{
    /**
     * Sync locales table from config (idempotent).
     */
    public function run(): void
    {
        if (! Schema::hasTable('locales')) {
            return;
        }

        $available = config('app.available_locales', ['en' => 'English']);
        $now = now();

        foreach ($available as $code => $name) {
            DB::table('locales')->updateOrInsert(
                ['code' => $code],
                [
                    'name' => $name,
                    'direction' => $this->guessDirection($code),
                    'store_enabled' => true,
                    'admin_enabled' => false,
                    'updated_at' => $now,
                    'created_at' => $now,
                ]
            );
        }
    }

    protected function guessDirection(string $code): string
    {
        $rtl = ['ar', 'fa', 'he', 'ur', 'ku', 'dv'];

        foreach ($rtl as $prefix) {
            if (str_starts_with(strtolower($code), $prefix)) {
                return 'rtl';
            }
        }

        return 'ltr';
    }
}
