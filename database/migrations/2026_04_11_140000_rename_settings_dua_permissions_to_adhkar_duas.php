<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private const MAP = [
        'settings.dua_sections' => 'adhkar_duas.dua_sections',
        'settings.dua_sections.create' => 'adhkar_duas.dua_sections.create',
        'settings.dua_sections.edit' => 'adhkar_duas.dua_sections.edit',
        'settings.dua_sections.delete' => 'adhkar_duas.dua_sections.delete',
        'settings.duas' => 'adhkar_duas.duas',
        'settings.duas.create' => 'adhkar_duas.duas.create',
        'settings.duas.edit' => 'adhkar_duas.duas.edit',
        'settings.duas.delete' => 'adhkar_duas.duas.delete',
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
