<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private const PERMS = [
        'map_locations',
        'map_locations.create',
        'map_locations.edit',
        'map_locations.delete',
    ];

    public function up(): void
    {
        if (! Schema::hasTable('roles')) {
            return;
        }

        $roles = DB::table('roles')->whereNotNull('permissions')->get(['id', 'permissions']);

        foreach ($roles as $role) {
            $raw = $role->permissions;
            $perms = is_string($raw) ? json_decode($raw, true) : (is_array($raw) ? $raw : []);
            if (! is_array($perms)) {
                continue;
            }

            $hasWebTheme = in_array('settings.web_theme', $perms, true)
                || in_array('settings.web_theme.homepage', $perms, true);

            if (! $hasWebTheme) {
                continue;
            }

            $changed = false;
            foreach (self::PERMS as $p) {
                if (! in_array($p, $perms, true)) {
                    $perms[] = $p;
                    $changed = true;
                }
            }

            if ($changed) {
                DB::table('roles')->where('id', $role->id)->update([
                    'permissions' => json_encode(array_values(array_unique($perms))),
                ]);
            }
        }
    }

    public function down(): void
    {
        if (! Schema::hasTable('roles')) {
            return;
        }

        $roles = DB::table('roles')->whereNotNull('permissions')->get(['id', 'permissions']);

        foreach ($roles as $role) {
            $raw = $role->permissions;
            $perms = is_string($raw) ? json_decode($raw, true) : (is_array($raw) ? $raw : []);
            if (! is_array($perms)) {
                continue;
            }

            $filtered = array_values(array_filter(
                $perms,
                static fn ($p) => ! in_array($p, self::PERMS, true)
            ));

            if (count($filtered) !== count($perms)) {
                DB::table('roles')->where('id', $role->id)->update([
                    'permissions' => json_encode($filtered),
                ]);
            }
        }
    }
};
