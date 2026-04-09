<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Grant the new locales "website" tab permission to roles that already have locales access.
     */
    public function up(): void
    {
        if (! Schema::hasTable('roles')) {
            return;
        }

        $roles = DB::table('roles')->whereNotNull('permissions')->get(['id', 'permissions']);

        foreach ($roles as $role) {
            $raw = $role->permissions;
            $perms = is_string($raw) ? json_decode($raw, true) : $raw;
            if (! is_array($perms)) {
                continue;
            }

            $hasLocales = in_array('settings.locales', $perms, true)
                || in_array('settings.locales.catalog', $perms, true);

            if (! $hasLocales || in_array('settings.locales.website', $perms, true)) {
                continue;
            }

            $perms[] = 'settings.locales.website';

            DB::table('roles')->where('id', $role->id)->update([
                'permissions' => json_encode(array_values(array_unique($perms))),
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (! Schema::hasTable('roles')) {
            return;
        }

        $roles = DB::table('roles')->whereNotNull('permissions')->get(['id', 'permissions']);

        foreach ($roles as $role) {
            $raw = $role->permissions;
            $perms = is_string($raw) ? json_decode($raw, true) : $raw;
            if (! is_array($perms)) {
                continue;
            }

            $perms = array_values(array_filter($perms, fn ($p) => $p !== 'settings.locales.website'));

            DB::table('roles')->where('id', $role->id)->update([
                'permissions' => json_encode($perms),
            ]);
        }
    }
};
