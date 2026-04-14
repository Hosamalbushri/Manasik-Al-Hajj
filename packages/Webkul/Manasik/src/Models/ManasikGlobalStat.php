<?php

namespace Webkul\Manasik\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ManasikGlobalStat extends Model
{
    protected $table = 'manasik_global_stats';

    public $incrementing = false;

    protected $keyType = 'int';

    protected $primaryKey = 'id';

    /**
     * Increment total full-guide completions (all pilgrims, including guests).
     * Caller should run inside a DB transaction when combined with other locks.
     */
    public static function incrementGuideFullCompletions(): void
    {
        if (! Schema::hasTable('manasik_global_stats')) {
            return;
        }

        $stat = static::query()->lockForUpdate()->find(1);
        if ($stat === null) {
            DB::table('manasik_global_stats')->insert([
                'id'                     => 1,
                'guide_full_completions' => 1,
                'created_at'             => now(),
                'updated_at'             => now(),
            ]);

            return;
        }

        $stat->increment('guide_full_completions');
    }
}
