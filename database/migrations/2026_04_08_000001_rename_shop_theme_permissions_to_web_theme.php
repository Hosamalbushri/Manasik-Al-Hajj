<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (! DB::getSchemaBuilder()->hasTable('roles')) {
            return;
        }

        $roles = DB::table('roles')->whereNotNull('permissions')->get(['id', 'permissions']);

        foreach ($roles as $role) {
            $raw = $role->permissions;
            if ($raw === null || $raw === '') {
                continue;
            }

            $decoded = json_decode($raw, true);
            if (! is_array($decoded)) {
                continue;
            }

            $numeric = array_keys($decoded) === range(0, count($decoded) - 1);
            if (! $numeric) {
                continue;
            }

            $out = [];
            $changed = false;

            foreach ($decoded as $perm) {
                if (! is_string($perm)) {
                    $out[] = $perm;

                    continue;
                }

                if (str_starts_with($perm, 'settings.shop_theme')) {
                    $out[] = str_replace('settings.shop_theme', 'settings.web_theme', $perm);
                    $changed = true;
                } else {
                    $out[] = $perm;
                }
            }

            if ($changed) {
                DB::table('roles')->where('id', $role->id)->update([
                    'permissions' => json_encode(array_values($out)),
                ]);
            }
        }
    }

    public function down(): void
    {
        if (! DB::getSchemaBuilder()->hasTable('roles')) {
            return;
        }

        $roles = DB::table('roles')->whereNotNull('permissions')->get(['id', 'permissions']);

        foreach ($roles as $role) {
            $raw = $role->permissions;
            if ($raw === null || $raw === '') {
                continue;
            }

            $decoded = json_decode($raw, true);
            if (! is_array($decoded)) {
                continue;
            }

            $numeric = array_keys($decoded) === range(0, count($decoded) - 1);
            if (! $numeric) {
                continue;
            }

            $out = [];
            $changed = false;

            foreach ($decoded as $perm) {
                if (! is_string($perm)) {
                    $out[] = $perm;

                    continue;
                }

                if (str_starts_with($perm, 'settings.web_theme')) {
                    $out[] = str_replace('settings.web_theme', 'settings.shop_theme', $perm);
                    $changed = true;
                } else {
                    $out[] = $perm;
                }
            }

            if ($changed) {
                DB::table('roles')->where('id', $role->id)->update([
                    'permissions' => json_encode(array_values($out)),
                ]);
            }
        }
    }
};
