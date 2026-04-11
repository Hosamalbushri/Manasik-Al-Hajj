@php($sessionFooterHtml = str_replace(':year', (string) date('Y'), (string) core()->getConfigData('general.settings.footer.label')))
@if (filled(trim(strip_tags((string) $sessionFooterHtml))))
    <div class="login-page-footer mx-auto w-full max-w-2xl px-4 text-center font-normal">
        {!! $sessionFooterHtml !!}
    </div>
@endif
