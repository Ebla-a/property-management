<header class="fixed top-0 inset-x-0 z-30 bg-white border-b shadow-sm">
    <div class="mx-auto px-6 lg:px-8 h-16 flex items-center justify-between">

        {{-- Left --}}
        <div class="flex items-center gap-6">

            {{-- User --}}
            <div class="flex items-center gap-3">
                <div class="hidden sm:flex flex-col">
                    <span class="text-sm font-medium">{{ auth()->user()->name }}</span>
                    <span class="text-xs text-gray-500">{{ auth()->user()->email }}</span>
                </div>

                <div class="h-10 w-10 rounded-full bg-indigo-50 flex items-center justify-center text-indigo-600 font-semibold">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
            </div>

            {{-- Role --}}
            @role('admin')
                <span class="px-2 py-0.5 text-xs rounded-full bg-indigo-100 text-indigo-700">Admin</span>
            @elserole('employee')
                <span class="px-2 py-0.5 text-xs rounded-full bg-blue-100 text-blue-700">Employee</span>
            @endrole

            {{-- Logout --}}
            <button
                type="button"
                onclick="window.dispatchEvent(new CustomEvent('open-modal', { detail: 'logout-modal' }))"
                class="px-3 py-1 rounded-full border border-gray-200 bg-white hover:bg-indigo-50 text-sm transition"
            >
                Logout
            </button>
        </div>

        {{-- Right --}}
        <div class="flex items-center gap-3">
            <span class="text-lg font-bold text-indigo-600 text-[32px]">RealEstate</span>
                        <span class="inline-flex h-10 w-10 items-center justify-center rounded-lg text-white font-bold">
                <img src="/logo.png" alt="logo">
            </span>
        </div>
    </div>
</header>
