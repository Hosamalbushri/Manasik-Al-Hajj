<x-web::layouts :title="__('hajj::account.title')" :hasHeader="true" :hasFooter="true">
    <div class="mx-auto max-w-lg px-4 py-12">
        <div class="rounded-2xl border border-[var(--shop-border-soft)] bg-white p-8 shadow-sm dark:border-gray-800 dark:bg-gray-900">
            <h1 class="mb-2 text-xl font-bold text-gray-900 dark:text-gray-100">
                {{ __('hajj::account.welcome', ['name' => $hajjUser->name]) }}
            </h1>

            <dl class="mt-6 space-y-3 text-sm text-gray-700 dark:text-gray-300">
                <div>
                    <dt class="font-medium text-gray-500 dark:text-gray-400">@lang('hajj::account.email')</dt>
                    <dd>{{ $hajjUser->email }}</dd>
                </div>
                @if ($hajjUser->phone)
                    <div>
                        <dt class="font-medium text-gray-500 dark:text-gray-400">@lang('hajj::account.phone')</dt>
                        <dd dir="ltr">{{ $hajjUser->phone }}</dd>
                    </div>
                @endif
            </dl>

            <form
                method="post"
                action="{{ route('hajj.session.destroy') }}"
                class="mt-8"
            >
                @csrf
                <button type="submit" class="secondary-button">
                    @lang('hajj::account.logout')
                </button>
            </form>
        </div>
    </div>
</x-web::layouts>
