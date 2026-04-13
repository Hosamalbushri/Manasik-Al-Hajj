<?php

namespace Webkul\Manasik\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Webkul\Manasik\Contracts\DuaSection as DuaSectionContract;

class DuaSection extends Model implements DuaSectionContract
{
    protected $table = 'manasik_dua_sections';

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
     * @return HasMany<Dua, $this>
     */
    public function duas(): HasMany
    {
        return $this->hasMany(Dua::class, 'manasik_dua_section_id')->orderBy('sort_order')->orderBy('id');
    }
}
