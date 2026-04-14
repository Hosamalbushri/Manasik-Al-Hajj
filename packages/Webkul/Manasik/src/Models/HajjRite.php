<?php

namespace Webkul\Manasik\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Webkul\Manasik\Contracts\HajjRite as HajjRiteContract;

class HajjRite extends Model implements HajjRiteContract
{
    protected $table = 'manasik_hajj_rites';

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
     * @return BelongsToMany<Dua, $this>
     */
    public function duas(): BelongsToMany
    {
        return $this->belongsToMany(Dua::class, 'manasik_hajj_rite_dua', 'manasik_hajj_rite_id', 'manasik_dua_id')
            ->withPivot('sort_order')
            ->orderByPivot('sort_order')
            ->orderByPivot('id');
    }
}
