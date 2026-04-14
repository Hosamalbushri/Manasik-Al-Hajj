{!! view_render_event('shop.components.layouts.header.mobile.before') !!}

@php
    use Illuminate\Support\Facades\Route;
    use Webkul\Web\Support\WebCoreStoreBranding;

    $logoUrl = WebCoreStoreBranding::storefrontLogoUrl() ?: config('web.logo_url');
    $webHomeUrl = Route::has('web.home.index') ? route('web.home.index') : url('/');

    $homeLabel = trim((string) core()->getConfigData('general.store.navigation.home_label'));
    $navItems = [];

    if (filter_var(core()->getConfigData('general.store.navigation.show_home'), FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE) ?? true) {
        $navItems[] = [
            'label' => $homeLabel !== '' ? $homeLabel : __('web::app.layout.nav.home'),
            'route' => 'web.home.index',
        ];
    }

    foreach ([1, 2] as $i) {
        if (! core()->getConfigData("general.store.navigation.custom_{$i}_enabled")) {
            continue;
        }

        $label = trim((string) core()->getConfigData("general.store.navigation.custom_{$i}_label"));
        $url = trim((string) core()->getConfigData("general.store.navigation.custom_{$i}_url"));

        if ($label === '' || $url === '') {
            continue;
        }

        $navItems[] = [
            'label' => $label,
            'url' => \Illuminate\Support\Str::startsWith($url, ['http://', 'https://']) ? $url : url($url),
        ];
    }

    $isMenuRouteActive = function (string $routeName): bool {
        if (request()->routeIs($routeName)) {
            return true;
        }

        if (\Illuminate\Support\Str::endsWith($routeName, '.index')) {
            $base = \Illuminate\Support\Str::beforeLast($routeName, '.index');

            return request()->routeIs($base.'.*');
        }

        return false;
    };

    $isMenuItemActive = function (array $item) use ($isMenuRouteActive): bool {
        if (! empty($item['route'])) {
            return $isMenuRouteActive($item['route']);
        }

        if (! empty($item['url'])) {
            return rtrim((string) request()->url(), '/') === rtrim((string) $item['url'], '/');
        }

        return false;
    };
@endphp

<div class="flex flex-wrap gap-4 px-4 pb-4 pt-6 shadow-sm lg:hidden">
    <div class="grid w-full grid-cols-[1fr_auto_1fr] items-center gap-2">
        <div class="flex min-w-0 items-center justify-start">
            @php
                $menuDrawerId = 'mobile-menu-drawer';
            @endphp

            <input
                id="{{ $menuDrawerId }}"
                type="checkbox"
                class="peer hidden"
            >

            <label
                for="{{ $menuDrawerId }}"
                class="flex cursor-pointer items-center"
                aria-label="{{ __('web::app.components.layouts.header.mobile.menu') }}"
            >
                <span class="icon-hamburger text-2xl text-navyBlue"></span>
            </label>

            <label
                for="{{ $menuDrawerId }}"
                class="fixed inset-0 z-[10002] hidden bg-black/40 opacity-0 transition-opacity peer-checked:block peer-checked:opacity-100 lg:hidden"
            ></label>

            <div
                class="fixed inset-y-0 z-[10003] w-[min(320px,86vw)] ltr:left-0 rtl:right-0 bg-white shadow-xl transition-transform duration-200 ease-in-out ltr:-translate-x-full rtl:translate-x-full peer-checked:translate-x-0"
                data-drawer="menu"
            >
                <div class="flex h-full flex-col overflow-auto border-r border-[color:var(--shop-border-soft)] bg-[color:var(--shop-surface)]">
                    <div class="flex items-center justify-between gap-3 border-b border-[color:var(--shop-border-soft)] bg-white px-4 py-3">
                        <p class="text-sm font-semibold text-[color:var(--shop-text)]">
                            {{ __('web::app.components.layouts.header.mobile.menu') }}
                        </p>

                        <label
                            for="{{ $menuDrawerId }}"
                            class="cursor-pointer rounded-md p-2 hover:bg-[color:var(--shop-surface)]"
                            aria-label="Close"
                        >
                            <span class="text-2xl leading-none text-[color:var(--shop-text-muted)]">×</span>
                        </label>
                    </div>

                    <div class="p-4">
                        <nav class="grid w-full gap-2">
                            @foreach ($navItems as $item)
                                @php $isActive = $isMenuItemActive($item); @endphp
                                <a
                                    href="{{ ! empty($item['route']) ? route($item['route']) : ($item['url'] ?? '#') }}"
                                    class="{{ $isActive ? 'border border-[color:var(--shop-primary)] bg-[color:var(--shop-primary)] text-white shadow-sm' : 'border border-[color:var(--shop-border-soft)] bg-white text-[color:var(--shop-text)] hover:border-[color:var(--shop-border-hover)] hover:bg-[color:var(--shop-surface)]' }} rounded-lg px-4 py-2.5 text-sm font-semibold transition"
                                    @if ($isActive) aria-current="page" @endif
                                >
                                    {{ $item['label'] }}
                                </a>
                            @endforeach

                        </nav>
                    </div>
                </div>
            </div>
        </div>

        <a
            href="{{ $webHomeUrl }}"
            class="mx-auto flex max-h-[30px] min-w-0 max-w-full items-center justify-center px-2"
            aria-label="{{ __('web::app.components.layouts.header.mobile.logo-alt') }}"
            title="{{ $webHomeUrl }}"
        >
            @if ($logoUrl)
                <img
                    src="{{ $logoUrl }}"
                    alt="{{ __('web::app.layout.store_logo_alt', ['name' => config('app.name')]) }}"
                    width="131"
                    height="29"
                    class="mx-auto block max-h-[30px] w-auto max-w-[min(100%,170px)] object-contain"
                >
            @else
                <span class="font-dmserif text-xl font-medium text-navyBlue">
                    {{ __('web::app.layout.brand') }}
                </span>
            @endif
        </a>

        <div class="flex min-w-0 items-center justify-end">
        </div>
    </div>

    <form
        action="{{ route('web.home.index') }}"
        class="mx-auto flex w-full max-w-xl justify-center"
        method="GET"
        role="search"
    >
        <label
            for="portal-search-mobile"
            class="sr-only"
        >
            {{ __('web::app.components.layouts.header.mobile.search') }}
        </label>

        <div class="relative w-full">
            <div class="icon-search pointer-events-none absolute top-3 flex items-center text-2xl max-md:text-xl max-sm:top-2.5 ltr:left-3 rtl:right-3"></div>

            <input
                id="portal-search-mobile"
                type="text"
                name="query"
                value="{{ request('query') }}"
                class="block w-full rounded-xl border border-[#E3E3E3] px-11 py-3.5 text-sm font-medium text-gray-900 max-md:rounded-lg max-md:px-10 max-md:py-3 max-md:font-normal max-sm:text-xs"
                placeholder="{{ __('web::app.components.layouts.header.mobile.search-text') }}"
                autocomplete="off"
            >
        </div>
    </form>
</div>

{!! view_render_event('shop.components.layouts.header.mobile.after') !!}
