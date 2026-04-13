@php
    $flashes = [];

    foreach (['success', 'warning', 'error', 'info'] as $type) {
        $message = session()->get($type);
        if ($message !== null && $message !== '') {
            $flashes[] = [
                'type' => $type,
                'message' => $message,
            ];
        }
    }
@endphp

{{-- Same layout as Bagisto Shop: desktop top-end, mobile bottom center (duplicate nodes per breakpoint). --}}
<div
    data-web-flash-desktop
    class="web-flash-group fixed top-5 z-[12000] grid justify-items-end gap-2.5 max-sm:hidden ltr:right-5 rtl:left-5"
    aria-live="polite"
>
    @foreach ($flashes as $flash)
        <x-web::flash-group.item :flash="$flash" />
    @endforeach
</div>

<div
    data-web-flash-mobile
    class="web-flash-group fixed bottom-10 left-1/2 z-[12000] grid -translate-x-1/2 -translate-y-1/2 transform justify-items-center gap-2.5 sm:hidden"
    aria-live="polite"
>
    @foreach ($flashes as $flash)
        <x-web::flash-group.item :flash="$flash" />
    @endforeach
</div>

@pushOnce('scripts', 'web-flash-close-label')
    <script>
        window.__webFlashCloseLabel = @json(__('web::app.components.layouts.flash-group.close'));
    </script>
@endpushOnce
