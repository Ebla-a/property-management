<div
    x-data="{ open: false }"
    x-show="open"
    x-cloak
    x-trap.noscroll="open"
    @open-modal.window="if ($event.detail === 'logout-modal') open = true"
    @keydown.escape.window="open = false"
    class="fixed inset-0 z-50 flex items-center justify-center"
>
    {{-- Overlay --}}
    <div
        x-show="open"
        x-transition.opacity
        class="fixed inset-0 bg-black/50"
        @click="open = false"
    ></div>

    {{-- Modal --}}
    <div
        x-show="open"
        x-transition.scale.opacity
        class="relative bg-white w-full max-w-md mx-4 rounded-2xl shadow-2xl p-6"
        @click.outside="open = false"
        role="dialog"
        aria-modal="true"
    >
        {{-- Icon --}}
        <div class="flex justify-center mb-4">
            <div class="w-14 h-14 rounded-full bg-indigo-50 flex items-center justify-center">
                <svg xmlns="http://www.w3.org/2000/svg"
                     class="h-7 w-7 text-indigo-600"
                     fill="none"
                     viewBox="0 0 24 24"
                     stroke="currentColor"
                     stroke-width="1.8">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h6a2 2 0 012 2v1"/>
                </svg>
            </div>
        </div>

        {{-- Content --}}
        <h2 class="text-center text-lg font-semibold text-indigo-600 mb-2">
           {{ __('messages.logout_modal.title') }}
        </h2>

        <p class="text-center text-sm text-gray-600 mb-6">
            {{ __('messages.logout_modal.message') }}
        </p>

        {{-- Actions --}}
        <div class="flex justify-center gap-3">
            <button
                type="button"
                @click="open = false"
                class="px-4 py-2 rounded-full bg-gray-100 text-gray-700 hover:bg-gray-200 transition"
            >
               {{ __('messages.logout_modal.cancel') }}
            </button>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button
                    type="submit"
                    class="px-5 py-2 rounded-full bg-indigo-600 text-white font-medium hover:bg-indigo-700 transition"
                >
                    {{ __('messages.logout_modal.confirm') }}
                </button>
            </form>
        </div>
    </div>
</div>
