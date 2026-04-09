<?php

namespace Webkul\Web\Models;

use Illuminate\Database\Eloquent\Model;

class WebThemeCustomization extends Model
{
    protected $table = 'shop_theme_customizations';

    protected $fillable = [
        'type',
        'name',
        'sort_order',
        'status',
        'theme_code',
        'options',
    ];

    protected function casts(): array
    {
        return [
            'status'     => 'boolean',
            'sort_order' => 'integer',
            'options'    => 'array',
        ];
    }
}
