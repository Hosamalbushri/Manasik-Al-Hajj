@prepend('scripts')
    <script
        type="module"
        src="{{ vite()->asset('js/chart.js') }}"
    ></script>
@endprepend

<x-admin::layouts>
    <x-slot:title>
        @lang('admin::app.dashboard.index.title')
    </x-slot>

    {!! view_render_event('admin.dashboard.index.header.before') !!}

    <div class="mb-5 flex items-center justify-between gap-4 max-sm:flex-wrap">
        {!! view_render_event('admin.dashboard.index.header.left.before') !!}

        <div class="grid gap-1.5">
            <p class="text-2xl font-semibold dark:text-white">
                @lang('admin::app.dashboard.index.title')
            </p>
        </div>

        {!! view_render_event('admin.dashboard.index.header.left.after') !!}
        {!! view_render_event('admin.dashboard.index.header.right.before') !!}
        {!! view_render_event('admin.dashboard.index.header.right.after') !!}
    </div>

    {!! view_render_event('admin.dashboard.index.header.after') !!}

    {!! view_render_event('admin.dashboard.index.content.before') !!}

    <div class="mt-3.5 flex flex-col gap-6">
        {!! view_render_event('admin.dashboard.index.content.left.before') !!}

        @include('admin::dashboard.index.manasik-charts')

        @include('admin::dashboard.index.manasik-over-all')

        @include('admin::dashboard.index.manasik-top-hajj-users')

        {!! view_render_event('admin.dashboard.index.content.left.after') !!}
    </div>

    {!! view_render_event('admin.dashboard.index.content.after') !!}
</x-admin::layouts>
