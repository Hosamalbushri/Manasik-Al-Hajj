<?php

namespace Webkul\Core\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\Core\Contracts\Locale as LocaleContract;

class Locale extends Model implements LocaleContract
{
    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'code',
        'name',
        'direction',
        'store_enabled',
        'admin_enabled',
        'logo_path',
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'store_enabled' => 'boolean',
            'admin_enabled' => 'boolean',
        ];
    }
}
