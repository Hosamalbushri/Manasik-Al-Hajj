<?php

namespace Webkul\Core;

use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Config;
use Webkul\Core\Repositories\CoreConfigRepository;
use Webkul\Core\SystemConfig\Item;

class SystemConfig
{
    /**
     * Items array.
     */
    public array $items = [];

    /**
     * Create a new class instance.
     *
     * @return void
     */
    public function __construct(protected CoreConfigRepository $coreConfigRepository) {}

    /**
     * Add Item.
     */
    public function addItem(Item $item): void
    {
        $this->items[] = $item;
    }

    /**
     * Get all configuration items.
     */
    public function getItems(): Collection
    {
        if (! $this->items) {
            $this->prepareConfigurationItems();
        }

        return collect($this->items)
            ->sortBy('sort');
    }

    /**
     * Retrieve Core Config
     */
    private function retrieveCoreConfig(): array
    {
        static $items;

        if ($items) {
            return $items;
        }

        return $items = config('core_config');
    }

    /**
     * Prepare configuration items.
     */
    public function prepareConfigurationItems()
    {
        $configWithDotNotation = [];

        foreach ($this->retrieveCoreConfig() as $item) {
            $configWithDotNotation[$item['key']] = $item;
        }

        $configs = Arr::undot(Arr::dot($configWithDotNotation));

        foreach ($configs as $configItem) {
            $subConfigItems = $this->processSubConfigItems($configItem);

            $this->addItem(new Item(
                children: $subConfigItems,
                fields: $configItem['fields'] ?? null,
                icon: $configItem['icon'] ?? null,
                key: $configItem['key'],
                name: $this->resolveConfigurationDisplayName($configItem['key'], (string) $configItem['name']),
                route: $configItem['route'] ?? null,
                info: trans($configItem['info']) ?? null,
                sort: $configItem['sort'],
            ));
        }
    }

    /**
     * Process sub config items.
     */
    private function processSubConfigItems($configItem): Collection
    {
        return collect($configItem)
            ->sortBy('sort')
            ->filter(fn ($value) => is_array($value) && isset($value['name']))
            ->map(function ($subConfigItem) {
                $configItemChildren = $this->processSubConfigItems($subConfigItem);

                return new Item(
                    children: $configItemChildren,
                    fields: $subConfigItem['fields'] ?? null,
                    icon: $subConfigItem['icon'] ?? null,
                    key: $subConfigItem['key'],
                    name: $this->resolveConfigurationDisplayName($subConfigItem['key'], (string) $subConfigItem['name']),
                    info: trans($subConfigItem['info']) ?? null,
                    route: $subConfigItem['route'] ?? null,
                    sort: $subConfigItem['sort'] ?? null,
                );
            });
    }

    /**
     * Get active configuration item.
     */
    public function getActiveConfigurationItem(): ?Item
    {
        if (! $slug = request()->route('slug')) {
            return null;
        }

        $activeItem = $this->getItems()->where('key', $slug)->first() ?? null;

        if (! $activeItem) {
            return null;
        }

        if ($slug2 = request()->route('slug2')) {
            $activeItem = $activeItem->getChildren()[$slug2];
        }

        return $activeItem;
    }

    /**
     * Get config field.
     */
    public function getConfigField(string $fieldName): ?array
    {
        foreach ($this->retrieveCoreConfig() as $coreData) {
            if (! isset($coreData['fields'])) {
                continue;
            }

            foreach ($coreData['fields'] as $field) {
                $name = $coreData['key'].'.'.$field['name'];

                if ($name == $fieldName) {
                    return $field;
                }
            }
        }

        return null;
    }

    /**
     * Get default config.
     */
    private function getDefaultConfig(string $field): mixed
    {
        $configFieldInfo = $this->getConfigField($field);

        $fields = explode('.', $field);

        array_shift($fields);

        $field = implode('.', $fields);

        $default = $configFieldInfo !== null
            ? ($configFieldInfo['default'] ?? null)
            : null;

        return Config::get($field, $default);
    }

    /**
     * Optional custom title for a configuration tree node (Configuration home cards and edit headings).
     * Stored as general.settings.configuration_tab_labels.{field} where field is "label_" + key with dots → underscores.
     */
    protected function configurationTabLabelOverride(string $configItemKey): ?string
    {
        static $cache = [];

        if (array_key_exists($configItemKey, $cache)) {
            return $cache[$configItemKey];
        }

        $field = 'label_'.str_replace('.', '_', $configItemKey);
        $code = 'general.settings.configuration_tab_labels.'.$field;

        $row = $this->coreConfigRepository->findOneWhere(['code' => $code]);
        $value = $row?->value;

        if (! is_string($value)) {
            return $cache[$configItemKey] = null;
        }

        $trimmed = trim($value);

        return $cache[$configItemKey] = ($trimmed === '' ? null : $trimmed);
    }

    /**
     * Resolved label: DB override if set, otherwise translated name from core_config definition.
     */
    protected function resolveConfigurationDisplayName(string $configItemKey, string $nameLangKey): string
    {
        $override = $this->configurationTabLabelOverride($configItemKey);

        return $override ?? trans($nameLangKey);
    }

    /**
     * Retrieve information for configuration
     */
    public function getConfigData(string $field): mixed
    {
        $coreConfigValue = $this->coreConfigRepository->findOneWhere([
            'code' => $field,
        ]);

        if (! $coreConfigValue) {
            return $this->getDefaultConfig($field);
        }

        return $coreConfigValue->value;
    }
}
