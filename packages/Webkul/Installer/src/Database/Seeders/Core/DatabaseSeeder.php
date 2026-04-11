<?php

namespace Webkul\Installer\Database\Seeders\Core;

use Illuminate\Database\Seeder;
use Webkul\Web\Database\Seeders\MapLocationsSeeder;
use Webkul\Web\Database\Seeders\WebDuaDefaultsSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @param  array  $parameters
     * @return void
     */
    public function run($parameters = [])
    {
        $this->call(CountriesSeeder::class, false, ['parameters' => $parameters]);
        $this->call(StatesSeeder::class, false, ['parameters' => $parameters]);
        $this->call(LocalesSeeder::class, false, ['parameters' => $parameters]);
        $this->call(WebThemeDefaultsSeeder::class, false, ['parameters' => $parameters]);

        $this->call(MapLocationsSeeder::class);
        $this->call(WebDuaDefaultsSeeder::class);
    }
}
