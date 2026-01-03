<header class="w-full bg-white border-b shadow-sm">
    <div class="max-w-7xl mx-auto px-6 py-3 flex items-center justify-between">

        {{-- Left Section --}}
        <div class="flex items-center gap-3">

            <h1 class="text-2xl font-bold">
                Dashboard
            </h1>

            @role('admin')
                <span class="px-2 py-0.5 text-xs rounded-lg bg-red-100 text-red-700">
                    Admin
                </span>
            @elserole('employee')
                <span class="px-2 py-0.5 text-xs rounded-lg bg-blue-100 text-blue-700">
                    Employee
                </span>
            @endrole
        </div>


        {{-- Right Side --}}
        <div class="flex items-center gap-4">

            {{-- Notifications --}}
            <button class="text-gray-600 hover:text-black">
                🔔
            </button>

            {{-- User --}}
            <span class="text-sm font-medium">
                {{ auth()->user()->name }}
            </span>

            {{-- Logout --}}
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button
                    class="px-3 py-1 border rounded-lg hover:bg-gray-100">
                    Logout
                </button>
            </form>
        </div>

    </div>
</header>
