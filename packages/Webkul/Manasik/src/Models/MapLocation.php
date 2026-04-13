<?php

namespace Webkul\Manasik\Models;

use Illuminate\Database\Eloquent\Model;
use Webkul\Manasik\Contracts\MapLocation as MapLocationContract;

class MapLocation extends Model implements MapLocationContract
{
    protected $table = 'manasik_map_locations';

    protected $guarded = ['id'];

    protected function casts(): array
    {
        return [
            'status' => 'boolean',
            'sort_order' => 'integer',
            'content' => 'array',
            'latitude' => 'float',
            'longitude' => 'float',
            'zoom' => 'integer',
        ];
    }
}
