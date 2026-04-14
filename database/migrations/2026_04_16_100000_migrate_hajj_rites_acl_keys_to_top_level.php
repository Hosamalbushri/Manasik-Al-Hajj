<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Roles that already received adhkar_duas.hajj_rites.* before the top-level hajj_rites.* rename.
     */
    private const OLD_TO_NEW = [
        'adhkar_duas.hajj_rites' => 'hajj_rites',
        'adhkar_duas.hajj_rites.create' => 'hajj_rites.create',
        'adhkar_duas.hajj_rites.edit' => 'hajj_rites.edit',
        'adhkar_duas.hajj_rites.delete' => 'hajj_rites.delete',
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

            $next = $perms;
            $changed = false;

            foreach (self::OLD_TO_NEW as $old => $new) {
                if (! in_array($old, $next, true)) {
                    continue;
                }

                $next = array_values(array_filter(
                    $next,
                    static fn ($p) => $p !== $old
                ));

                if (! in_array($new, $next, true)) {
                    $next[] = $new;
                }

                $changed = true;
            }

            if ($changed) {
                DB::table('roles')->where('id', $role->id)->update([
                    'permissions' => json_encode(array_values(array_unique($next))),
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

            $next = $perms;
            $changed = false;

            foreach (self::OLD_TO_NEW as $old => $new) {
                if (! in_array($new, $next, true)) {
                    continue;
                }

                $next = array_values(array_filter(
                    $next,
                    static fn ($p) => $p !== $new
                ));

                if (! in_array($old, $next, true)) {
                    $next[] = $old;
                }

                $changed = true;
            }

            if ($changed) {
                DB::table('roles')->where('id', $role->id)->update([
                    'permissions' => json_encode(array_values(array_unique($next))),
                ]);
            }
        }
    }
};
