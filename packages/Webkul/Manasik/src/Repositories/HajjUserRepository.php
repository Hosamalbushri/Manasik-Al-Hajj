<?php

namespace Webkul\Manasik\Repositories;

use Webkul\Core\Eloquent\Repository;
use Webkul\Manasik\Models\HajjUser;

class HajjUserRepository extends Repository
{
    public function model(): string
    {
        return HajjUser::class;
    }
}
