<x-web::layouts :title="__('hajj::register.title')" :hasHeader="true" :hasFooter="true">
    <div class="mx-auto max-w-md px-4 py-12">
        <div class="rounded-2xl border border-[var(--shop-border-soft)] bg-white p-8 shadow-sm dark:border-gray-800 dark:bg-gray-900">
            <h1 class="mb-6 text-xl font-bold text-gray-900 dark:text-gray-100">
                @lang('hajj::register.heading')
            </h1>

            <form
                method="post"
                action="{{ route('hajj.register.store') }}"
                class="flex flex-col gap-4"
            >
                @csrf

                <div>
                    <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300" for="hajj-name">
                        @lang('hajj::register.name')
                    </label>
                    <input
                        id="hajj-name"
                        name="name"
                        type="text"
                        value="{{ old('name') }}"
                        required
                        autocomplete="name"
                        class="w-full rounded-lg border border-gray-300 px-3 py-2 text-gray-900 focus:border-[var(--shop-primary)] focus:outline-none focus:ring-2 focus:ring-[var(--shop-ring)] dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100"
                    >
                    @error('name')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300" for="hajj-reg-email">
                        @lang('hajj::register.email')
                    </label>
                    <input
                        id="hajj-reg-email"
                        name="email"
                        type="email"
                        value="{{ old('email') }}"
                        required
                        autocomplete="email"
                        class="w-full rounded-lg border border-gray-300 px-3 py-2 text-gray-900 focus:border-[var(--shop-primary)] focus:outline-none focus:ring-2 focus:ring-[var(--shop-ring)] dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100"
                    >
                    @error('email')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300" for="hajj-phone">
                        @lang('hajj::register.phone')
                    </label>
                    <input
                        id="hajj-phone"
                        name="phone"
                        type="tel"
                        value="{{ old('phone') }}"
                        autocomplete="tel"
                        class="w-full rounded-lg border border-gray-300 px-3 py-2 text-gray-900 focus:border-[var(--shop-primary)] focus:outline-none focus:ring-2 focus:ring-[var(--shop-ring)] dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100"
                    >
                    @error('phone')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300" for="hajj-reg-password">
                        @lang('hajj::register.password')
                    </label>
                    <input
                        id="hajj-reg-password"
                        name="password"
                        type="password"
                        required
                        autocomplete="new-password"
                        class="w-full rounded-lg border border-gray-300 px-3 py-2 text-gray-900 focus:border-[var(--shop-primary)] focus:outline-none focus:ring-2 focus:ring-[var(--shop-ring)] dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100"
                    >
                    @error('password')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300" for="hajj-password-confirm">
                        @lang('hajj::register.password_confirm')
                    </label>
                    <input
                        id="hajj-password-confirm"
                        name="password_confirmation"
                        type="password"
                        required
                        autocomplete="new-password"
                        class="w-full rounded-lg border border-gray-300 px-3 py-2 text-gray-900 focus:border-[var(--shop-primary)] focus:outline-none focus:ring-2 focus:ring-[var(--shop-ring)] dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100"
                    >
                </div>

                <button
                    type="submit"
                    class="primary-button w-full justify-center"
                >
                    @lang('hajj::register.submit')
                </button>
            </form>

            <p class="mt-6 text-center text-sm text-gray-600 dark:text-gray-400">
                <a href="{{ route('hajj.session.create') }}" class="font-medium text-[var(--shop-primary)] hover:underline">
                    @lang('hajj::register.login')
                </a>
            </p>
        </div>
    </div>
</x-web::layouts>
