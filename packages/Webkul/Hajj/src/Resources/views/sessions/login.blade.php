<x-web::layouts :title="__('hajj::session.title')" :hasHeader="true" :hasFooter="true">
    <div class="mx-auto max-w-md px-4 py-12">
        <div class="rounded-2xl border border-[var(--shop-border-soft)] bg-white p-8 shadow-sm dark:border-gray-800 dark:bg-gray-900">
            <h1 class="mb-6 text-xl font-bold text-gray-900 dark:text-gray-100">
                @lang('hajj::session.heading')
            </h1>

            <form
                method="post"
                action="{{ route('hajj.session.store') }}"
                class="flex flex-col gap-4"
            >
                @csrf

                <div>
                    <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300" for="hajj-email">
                        @lang('hajj::session.email')
                    </label>
                    <input
                        id="hajj-email"
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
                    <label class="mb-1 block text-sm font-medium text-gray-700 dark:text-gray-300" for="hajj-password">
                        @lang('hajj::session.password')
                    </label>
                    <input
                        id="hajj-password"
                        name="password"
                        type="password"
                        required
                        autocomplete="current-password"
                        class="w-full rounded-lg border border-gray-300 px-3 py-2 text-gray-900 focus:border-[var(--shop-primary)] focus:outline-none focus:ring-2 focus:ring-[var(--shop-ring)] dark:border-gray-600 dark:bg-gray-800 dark:text-gray-100"
                    >
                </div>

                <label class="flex items-center gap-2 text-sm text-gray-700 dark:text-gray-300">
                    <input type="checkbox" name="remember" value="1" @checked(old('remember'))>
                    @lang('hajj::session.remember')
                </label>

                <button
                    type="submit"
                    class="primary-button w-full justify-center"
                >
                    @lang('hajj::session.submit')
                </button>
            </form>

            <p class="mt-6 text-center text-sm text-gray-600 dark:text-gray-400">
                <a href="{{ route('hajj.register.create') }}" class="font-medium text-[var(--shop-primary)] hover:underline">
                    @lang('hajj::session.register')
                </a>
            </p>
        </div>
    </div>
</x-web::layouts>
