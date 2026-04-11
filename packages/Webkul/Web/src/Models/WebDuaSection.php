<?php

namespace Webkul\Web\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WebDuaSection extends Model
{
    protected $table = 'web_dua_sections';

    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'status'     => 'boolean',
            'sort_order' => 'integer',
            'content'    => 'array',
        ];
    }

    /**
     * @return HasMany<WebDua, $this>
     */
    public function duas(): HasMany
    {
        return $this->hasMany(WebDua::class, 'web_dua_section_id')->orderBy('sort_order')->orderBy('id');
    }
}
