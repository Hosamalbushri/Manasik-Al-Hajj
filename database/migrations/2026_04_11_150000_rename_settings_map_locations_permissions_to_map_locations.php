<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private const MAP = [
        'settings.map_locations' => 'map_locations',
        'settings.map_locations.create' => 'map_locations.create',
        'settings.map_locations.edit' => 'map_locations.edit',
        'settings.map_locations.delete' => 'map_locations.delete',
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

            $changed = false;
            foreach ($perms as $i => $p) {
                if (isset(self::MAP[$p])) {
                    $perms[$i] = self::MAP[$p];
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

        $reverse = array_flip(self::MAP);

        $roles = DB::table('roles')->whereNotNull('permissions')->get(['id', 'permissions']);

        foreach ($roles as $role) {
            $raw = $role->permissions;
            $perms = is_string($raw) ? json_decode($raw, true) : (is_array($raw) ? $raw : []);
            if (! is_array($perms)) {
                continue;
            }

            $changed = false;
            foreach ($perms as $i => $p) {
                if (isset($reverse[$p])) {
                    $perms[$i] = $reverse[$p];
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
};
