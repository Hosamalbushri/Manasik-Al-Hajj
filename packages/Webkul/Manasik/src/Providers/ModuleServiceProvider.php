<?php

namespace Webkul\Manasik\Providers;

use Webkul\Core\Providers\BaseModuleServiceProvider;
use Webkul\Manasik\Models\Dua;
use Webkul\Manasik\Models\DuaSection;
use Webkul\Manasik\Models\HajjRite;
use Webkul\Manasik\Models\HajjUser;
use Webkul\Manasik\Models\MapLocation;

class ModuleServiceProvider extends BaseModuleServiceProvider
{
    protected $models = [
        HajjUser::class,
        MapLocation::class,
        DuaSection::class,
        Dua::class,
        HajjRite::class,
    ];
}
