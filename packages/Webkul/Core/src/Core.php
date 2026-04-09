<?php

namespace Webkul\Core;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;
use Webkul\Core\Repositories\CoreConfigRepository;
use Webkul\Core\Repositories\CountryRepository;
use Webkul\Core\Repositories\CountryStateRepository;
use Webkul\Core\Repositories\LocaleRepository;

class Core
{
    /**
     * The Krayin version.
     *
     * @var string
     */
    const KRAYIN_VERSION = '2.2.0';

    /**
     * Create a new instance.
     *
     * @return void
     */
    public function __construct(
        protected CountryRepository $countryRepository,
        protected CoreConfigRepository $coreConfigRepository,
        protected CountryStateRepository $countryStateRepository,
        protected LocaleRepository $localeRepository
    ) {}

    /**
     * Get the version number of the Krayin.
     *
     * @return string
     */
    public function version()
    {
        return static::KRAYIN_VERSION;
    }

    /**
     * Retrieve all timezones.
     */
    public function timezones(): array
    {
        $timezones = [];

        foreach (timezone_identifiers_list() as $timezone) {
            $timezones[$timezone] = $timezone;
        }

        return $timezones;
    }

    /**
     * Select options for locales enabled on the storefront (session "locale", web UI).
     */
    public function storeLocales(): array
    {
        return $this->buildStoreLocaleOptions();
    }

    /**
     * Select options for the admin panel UI. Fixed list from config (not the locales table).
     */
    public function adminLocales(): array
    {
        return $this->localesFromConfig();
    }

    /**
     * Backward-compatible alias: same as {@see storeLocales()}.
     */
    public function locales(): array
    {
        return $this->storeLocales();
    }

    /**
     * Whether the code may be used as the storefront / web UI language.
     */
    public function isStoreLocaleAllowed(string $code): bool
    {
        $code = strtolower($code);

        if (! Schema::hasTable('locales')) {
            return array_key_exists($code, config('app.available_locales', []));
        }

        $model = $this->localeRepository->getModel();

        if (! $model->newQuery()->where('store_enabled', true)->exists()) {
            return array_key_exists($code, config('app.available_locales', []));
        }

        return $model->newQuery()
            ->where('code', $code)
            ->where('store_enabled', true)
            ->exists();
    }

    /**
     * Whether the code may be used for the admin panel UI (config only; independent of DB locales).
     */
    public function isAdminLocaleAllowed(string $code): bool
    {
        return array_key_exists($code, config('app.available_locales', []));
    }

    /**
     * All locale codes in DB (for translatable / integrations). Falls back to config keys.
     *
     * @return list<string>
     */
    public function getAllLocaleCodes(): array
    {
        if (! Schema::hasTable('locales')) {
            return array_keys(config('app.available_locales', []));
        }

        $codes = $this->localeRepository->getModel()
            ->newQuery()
            ->where('store_enabled', true)
            ->orderBy('code')
            ->pluck('code')
            ->all();

        return $codes !== [] ? $codes : array_keys(config('app.available_locales', []));
    }

    /**
     * Storefront / website locale options from the locales table.
     *
     * @return list<array{title: string, value: string}>
     */
    protected function buildStoreLocaleOptions(): array
    {
        if (! Schema::hasTable('locales')) {
            return $this->localesFromConfig();
        }

        $rows = $this->localeRepository->getModel()
            ->newQuery()
            ->where('store_enabled', true)
            ->orderBy('name')
            ->get();

        if ($rows->isEmpty()) {
            return $this->localesFromConfig();
        }

        return $rows->map(fn ($locale) => [
            'title' => $locale->name,
            'value' => $locale->code,
        ])->all();
    }

    /**
     * @return list<array{title: string, value: string}>
     */
    protected function localesFromConfig(): array
    {
        $options = [];

        foreach (config('app.available_locales', []) as $key => $title) {
            $options[] = [
                'title' => $title,
                'value' => $key,
            ];
        }

        return $options;
    }

    /**
     * Retrieve all countries.
     *
     * @return Collection
     */
    public function countries()
    {
        return $this->countryRepository->all();
    }

    /**
     * Returns country name by code.
     */
    public function country_name(string $code): string
    {
        $country = $this->countryRepository->findOneByField('code', $code);

        return $country ? $country->name : '';
    }

    /**
     * Returns state name by code.
     */
    public function state_name(string $code): string
    {
        $state = $this->countryStateRepository->findOneByField('code', $code);

        return $state ? $state->name : $code;
    }

    /**
     * Retrieve all country states.
     *
     * @return Collection
     */
    public function states(string $countryCode)
    {
        return $this->countryStateRepository->findByField('country_code', $countryCode);
    }

    /**
     * Retrieve all grouped states by country code.
     *
     * @return Collection
     */
    public function groupedStatesByCountries()
    {
        $collection = [];

        foreach ($this->countryStateRepository->all() as $state) {
            $collection[$state->country_code][] = $state->toArray();
        }

        return $collection;
    }

    /**
     * Retrieve all grouped states by country code.
     *
     * @return Collection
     */
    public function findStateByCountryCode($countryCode = null, $stateCode = null)
    {
        $collection = [];

        $collection = $this->countryStateRepository->findByField([
            'country_code' => $countryCode,
            'code' => $stateCode,
        ]);

        if (count($collection)) {
            return $collection->first();
        } else {
            return false;
        }
    }

    /**
     * Create singleton object through single facade.
     *
     * @param  string  $className
     * @return mixed
     */
    public function getSingletonInstance($className)
    {
        static $instances = [];

        if (array_key_exists($className, $instances)) {
            return $instances[$className];
        }

        return $instances[$className] = app($className);
    }

    /**
     * Format date
     *
     * @return string
     */
    public function formatDate($date, $format = 'd M Y h:iA')
    {
        return Carbon::parse($date)->format($format);
    }

    /**
     * Week range.
     *
     * @param  string  $date
     * @param  int  $day
     * @return string
     */
    public function xWeekRange($date, $day)
    {
        $ts = strtotime($date);

        if (! $day) {
            $start = (date('D', $ts) == 'Sun') ? $ts : strtotime('last sunday', $ts);

            return date('Y-m-d', $start);
        } else {
            $end = (date('D', $ts) == 'Sat') ? $ts : strtotime('next saturday', $ts);

            return date('Y-m-d', $end);
        }
    }

    /**
     * Return currency symbol from currency code.
     *
     * @param  float  $price
     * @return string
     */
    public function currencySymbol($code)
    {
        $formatter = new \NumberFormatter(app()->getLocale().'@currency='.$code, \NumberFormatter::CURRENCY);

        return $formatter->getSymbol(\NumberFormatter::CURRENCY_SYMBOL);
    }

    /**
     * Format price with base currency symbol. This method also give ability to encode
     * the base currency symbol and its optional.
     *
     * @param  float  $price
     * @return string
     */
    public function formatBasePrice($price)
    {
        if (is_null($price)) {
            $price = 0;
        }

        $formatter = new \NumberFormatter(app()->getLocale(), \NumberFormatter::CURRENCY);

        return $formatter->formatCurrency($price, config('app.currency'));
    }

    /**
     * Get the config field.
     */
    public function getConfigField(string $fieldName): ?array
    {
        return system_config()->getConfigField($fieldName);
    }

    /**
     * Retrieve information for configuration.
     */
    public function getConfigData(string $field): mixed
    {
        return system_config()->getConfigData($field);
    }
}
