<?php

namespace Webkul\Web\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Routing\Controller;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;
use Webkul\Web\Models\ThemeCustomization;
use Webkul\Web\Models\WebThemeCustomization;
use Webkul\Web\Repositories\WebThemeCustomizationRepository;

class HomeController extends Controller
{
    public function __construct(
        protected WebThemeCustomizationRepository $webThemeCustomizationRepository
    ) {}

    public function index(): View
    {
        $customizations = $this->resolvedCustomizations();

        return view('web::home.index', [
            'customizations' => $customizations,
            'homeSeo'        => config('web.home_seo', []),
        ]);
    }

    /**
     * @return Collection<int, object>
     */
    protected function resolvedCustomizations(): Collection
    {
        $themeCode = config('web.storefront_theme_code', 'web');

        if (
            Schema::hasTable('shop_theme_customizations')
            && Schema::hasColumn('shop_theme_customizations', 'options')
            && $this->webThemeCustomizationRepository->getModel()
                ->newQuery()
                ->where('theme_code', $themeCode)
                ->where('status', true)
                ->exists()
        ) {
            return $this->resolvedFromDatabase($themeCode);
        }

        return $this->resolvedFromConfig();
    }

    /**
     * @return Collection<int, object>
     */
    protected function resolvedFromDatabase(string $themeCode): Collection
    {
        return $this->webThemeCustomizationRepository
            ->getActiveForStorefront($themeCode)
            ->filter(fn (WebThemeCustomization $row) => $row->type !== ThemeCustomization::WEB_HEADER
                && $row->type !== ThemeCustomization::WEB_FOOTER)
            ->values()
            ->map(function (WebThemeCustomization $row) {
                $options = is_array($row->options) ? $row->options : [];
                $obj = (object) [
                    'id'      => $row->id,
                    'type'    => $row->type,
                    'options' => $options,
                ];

                return $obj;
            });
    }

    /**
     * @return Collection<int, object>
     */
    protected function resolvedFromConfig(): Collection
    {
        $rows = collect(config('web.home_customizations', []))
            ->filter(fn (array $row) => (int) ($row['status'] ?? 1) === 1)
            ->filter(function (array $row) {
                $type = (string) ($row['type'] ?? '');

                return $type !== ThemeCustomization::WEB_HEADER
                    && $type !== ThemeCustomization::WEB_FOOTER;
            })
            ->sortBy(fn (array $row, int $i) => $row['sort_order'] ?? $i)
            ->values();

        return $rows->map(function (array $row) {
            $type = (string) ($row['type'] ?? '');
            $options = is_array($row['options'] ?? null) ? $row['options'] : [];

            $row['options'] = $options;

            return (object) $row;
        });
    }
}
