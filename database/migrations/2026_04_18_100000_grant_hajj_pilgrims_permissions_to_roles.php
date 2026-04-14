<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private const PERM = 'hajj_pilgrims';

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

            $hasHajjRites = in_array('hajj_rites', $perms, true);

            if (! $hasHajjRites) {
                continue;
            }

            if (in_array(self::PERM, $perms, true)) {
                continue;
            }

            $perms[] = self::PERM;

            DB::table('roles')->where('id', $role->id)->update([
                'permissions' => json_encode(array_values(array_unique($perms))),
            ]);
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
                static fn ($p) => $p !== self::PERM
            ));

            if (count($filtered) !== count($perms)) {
                DB::table('roles')->where('id', $role->id)->update([
                    'permissions' => json_encode($filtered),
                ]);
            }
        }
    }
};
