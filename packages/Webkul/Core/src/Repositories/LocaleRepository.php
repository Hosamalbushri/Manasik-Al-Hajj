<?php

namespace Webkul\Core\Repositories;

use Webkul\Core\Contracts\Locale as LocaleContract;
use Webkul\Core\Eloquent\Repository;

class LocaleRepository extends Repository
{
    /**
     * Specify model class name.
     */
    public function model(): string
    {
        return LocaleContract::class;
    }
}
