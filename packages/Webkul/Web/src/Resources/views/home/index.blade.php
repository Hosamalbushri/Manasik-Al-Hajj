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
    @foreach ($customizations as $customization)
        @php
            $data = $customization->options ?? [];
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

            @case (ThemeCustomization::FOOTER_LINKS)
                <x-web::home.footer-links
                    :options="$data"
                    :aria-label="trans('web::app.home.index.footer-links')"
                />

                @break

            @case (ThemeCustomization::SERVICES_CONTENT)
                <x-web::home.services-strip
                    :options="$data"
                    :aria-label="trans('web::app.home.index.services-strip')"
                />

                @break

            @case (ThemeCustomization::PRODUCT_CAROUSEL)
                @break

            @case (ThemeCustomization::PORTAL_FOOTER)
                @break

            @case (ThemeCustomization::WEB_HEADER)
            @case (ThemeCustomization::WEB_FOOTER)
                @break

            @default
        @endswitch
    @endforeach
</x-web::layouts>
