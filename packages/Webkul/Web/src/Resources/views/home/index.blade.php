@php
    use Webkul\Web\Models\ThemeCustomization;

    $metaTitle = $homeSeo['meta_title'] ?? __('web::app.home.seo.meta-title');
    $metaDescription = $homeSeo['meta_description'] ?? __('web::app.home.seo.meta-description');
    $metaKeywords = $homeSeo['meta_keywords'] ?? __('web::app.home.seo.meta-keywords');
@endphp

@push('meta')
    <meta name="title" content="{{ $metaTitle }}" />
    <meta name="description" content="{{ $metaDescription }}" />
    <meta name="keywords" content="{{ $metaKeywords }}" />
@endpush

<x-web::layouts :title="$metaTitle">
    @foreach ($customizations as $homeSectionIndex => $customization)
        @php
            $data = $customization->options ?? [];
            $__homeSectionDbId = data_get($customization, 'id');
            $homeSectionInstanceId = (int) ($__homeSectionDbId !== null ? $__homeSectionDbId : $homeSectionIndex);
        @endphp

        @switch ($customization->type)
            @case (ThemeCustomization::IMAGE_CAROUSEL)
                <x-web::carousel
                    :options="$data"
                    :aria-label="trans('web::app.home.index.image-carousel')"
                />

                @break

            @case (ThemeCustomization::IMMERSIVE_HERO)
                <x-web::home.immersive-hero
                    :options="$data"
                    :aria-label="__('web::app.home.index.immersive-hero')"
                />

                @break

            @case (ThemeCustomization::SUPPLICATIONS_CONTENT)
                <x-web::home.supplications
                    :options="$data"
                    :aria-label="__('web::app.home.index.supplications')"
                />

                @break

            @case (ThemeCustomization::SECTION_DIVIDER)
                <x-web::home.section-divider
                    :options="$data"
                    :aria-label="__('web::app.home.index.section-divider')"
                />

                @break

            @case (ThemeCustomization::MAPS_SHOWCASE)
                <x-web::home.maps-showcase
                    :options="$data"
                    :section-id="$homeSectionInstanceId"
                    :aria-label="__('web::app.home.index.maps-showcase')"
                />

                @break

            @case (ThemeCustomization::MANASIK_SHOWCASE)
                <x-web::home.manasik-showcase
                    :options="$data"
                    :section-id="$homeSectionInstanceId"
                    :aria-label="__('web::app.home.index.manasik-showcase')"
                />

                @break

            @case (ThemeCustomization::PRAYER_TIMES)
                <x-web::home.prayer-times
                    :options="$data"
                    :section-id="$homeSectionInstanceId"
                    :aria-label="__('web::app.home.index.prayer-times')"
                />

                @break

            @case (ThemeCustomization::STATIC_CONTENT)
                @if (! empty($data['css']))
                    @push('styles')
                        <style>
                            {{ $data['css'] }}
                        </style>
                    @endpush
                @endif

                @if (! empty($data['view']))
                    @include($data['view'])
                @endif

                @if (! empty($data['html']))
                    {!! $data['html'] !!}
                @endif

                @break

            @case (ThemeCustomization::WEB_HEADER)
            @case (ThemeCustomization::WEB_FOOTER)
                @break

            @default
        @endswitch
    @endforeach
</x-web::layouts>
