<?php

namespace Webkul\Web\Models;

use Illuminate\Database\Eloquent\Model;

class WebMapLocation extends Model
{
    protected $table = 'web_map_locations';

    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'status'      => 'boolean',
            'sort_order'  => 'integer',
            'content'     => 'array',
            'latitude'    => 'float',
            'longitude'   => 'float',
            'zoom'        => 'integer',
        ];
    }
}
