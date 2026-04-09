<?php

use App\Providers\AppServiceProvider;
use Barryvdh\DomPDF\ServiceProvider;
use Konekt\Concord\ConcordServiceProvider;
use Prettus\Repository\Providers\RepositoryServiceProvider;
use Webkul\Admin\Providers\AdminServiceProvider;
use Webkul\Core\Providers\CoreServiceProvider;
use Webkul\DataGrid\Providers\DataGridServiceProvider;
use Webkul\Installer\Providers\InstallerServiceProvider;
use Webkul\User\Providers\UserServiceProvider;
use Webkul\Web\Providers\WebServiceProvider;

return [
    /*
     * Package Service Providers...
     */
    ServiceProvider::class,
    ConcordServiceProvider::class,
    RepositoryServiceProvider::class,

    /*
     * Application Service Providers...
     */
    AppServiceProvider::class,

    /*
     * Webkul Service Providers...
     */
    AdminServiceProvider::class,
    CoreServiceProvider::class,
    DataGridServiceProvider::class,
    InstallerServiceProvider::class,
    UserServiceProvider::class,
    WebServiceProvider::class,
];
