<?php

namespace Webkul\Web\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WebDua extends Model
{
    protected $table = 'web_duas';

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
     * @return BelongsTo<WebDuaSection, $this>
     */
    public function section(): BelongsTo
    {
        return $this->belongsTo(WebDuaSection::class, 'web_dua_section_id');
    }
}
