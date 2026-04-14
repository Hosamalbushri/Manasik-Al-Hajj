<?php

namespace Webkul\Manasik\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Webkul\Manasik\Contracts\Dua as DuaContract;

class Dua extends Model implements DuaContract
{
    protected $table = 'manasik_duas';

    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'status' => 'boolean',
            'sort_order' => 'integer',
            'content' => 'array',
        ];
    }

    /**
     * @return BelongsTo<DuaSection, $this>
     */
    public function section(): BelongsTo
    {
        return $this->belongsTo(DuaSection::class, 'manasik_dua_section_id');
    }

    /**
     * @return BelongsToMany<HajjRite, $this>
     */
    public function hajjRites(): BelongsToMany
    {
        return $this->belongsToMany(HajjRite::class, 'manasik_hajj_rite_dua', 'manasik_dua_id', 'manasik_hajj_rite_id')
            ->withPivot('sort_order');
    }
}
