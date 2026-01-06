<header class="fixed top-0 inset-x-0 z-30 bg-white border-b shadow-sm">
    <div class="mx-auto px-6 lg:px-8 h-16 flex items-center justify-between">

        {{-- Left Side: Controls (User info, Role, Notifications, Logout) --}}
        <div class="flex items-center gap-6">
            {{-- User info --}}
            <div class="flex items-center gap-3">
                <div class="hidden sm:flex flex-col text-left">
                    <span class="text-sm font-medium">{{ auth()->user()->name }}</span>
                    <span class="text-xs text-gray-500">{{ auth()->user()->email }}</span>
                </div>

                <div class="h-10 w-10 rounded-full bg-indigo-50 flex items-center justify-center text-indigo-600 font-semibold">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
            </div>

            {{-- Role badge --}}
            @role('admin')
                <span class="px-2 py-0.5 text-xs rounded-lg bg-indigo-100 text-indigo-700">
                    Admin
                </span>
            @elserole('employee')
                <span class="px-2 py-0.5 text-xs rounded-lg bg-blue-100 text-blue-700">
                    Employee
                </span>
            @endrole

            {{-- Notifications --}}
            <button class="p-2 rounded-md hover:bg-gray-100 text-gray-600" title="Notifications">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none"
                     viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 10-12 0v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0a3 3 0 11-6 0h6z"/>
                </svg>
            </button>

            {{-- Logout button --}}
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                        class="px-3 py-1 rounded-lg border border-gray-200 bg-white hover:bg-gray-50 text-sm">
                    Logout
                </button>
            </form>
        </div>

        {{-- Right Side: Brand --}}
        <div class="flex items-center gap-4">
            <a href="{{ url('/') }}" class="flex items-center gap-3">
                <!-- Logo circle -->
                <span class="inline-flex items-center justify-center h-10 w-10 rounded-lg bg-indigo-600 text-white font-bold">
                    RS
                </span>
                <span class="text-lg font-bold text-indigo-600">RealEstateSys</span>
            </a>
            <span class="text-sm text-gray-500 hidden md:inline-block">Property Management System</span>
        </div>

    </div>
</header>
