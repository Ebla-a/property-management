<aside class="hidden lg:block">
    <div class="fixed top-16 left-0 w-64 h-[calc(100vh-64px)] bg-white border-r shadow-sm overflow-auto px-4 py-6">
        <div class="mb-6">
            <h3 class="text-lg font-bold text-gray-800">Dashboard</h3>
            <p class="text-xs text-gray-500 mt-1">Property Management System</p>
        </div>

        <nav class="space-y-2">
            {{-- Dashboard --}}
            <a href="{{ url('dashboard') }}"
                class="flex items-center gap-3 p-2 rounded-lg transition-colors {{ request()->is('dashboard') ? 'bg-indigo-50 text-indigo-700 font-semibold' : 'text-gray-700 hover:bg-gray-50' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M10.707 1.293a1 1 0 00-1.414 0L2 8.586V18a1 1 0 001 1h5v-5h4v5h5a1 1 0 001-1V8.586l-7.293-7.293z" />
                </svg>
                <span>Home</span>
            </a>
            @role('admin')

            {{-- Properties --}}
            <a href="{{ url('dashboard/properties') }}"
                class="flex items-center gap-3 p-2 rounded-lg transition-colors {{ request()->is('dashboard/properties*') ? 'bg-indigo-50 text-indigo-700 font-semibold' : 'text-gray-700 hover:bg-gray-50' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 21h18M4 8h16M4 3h16v5H4z" />
                </svg>
                <span>Properties</span>
            </a>

            {{-- Amenities --}}
            <a href="{{ route('dashboard.amenities.index') }}"
                class="flex items-center gap-3 p-2 rounded-lg transition-colors {{ request()->is('dashboard/amenities*') ? 'bg-indigo-50 text-indigo-700 font-semibold' : 'text-gray-700 hover:bg-gray-50' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                </svg>
                <span>Amenities</span>
            </a>

            {{-- Bookings --}}
            <a href="{{ url('dashboard/bookings') }}"
                class="flex items-center gap-3 p-2 rounded-lg transition-colors {{ request()->is('dashboard/bookings*') ? 'bg-indigo-50 text-indigo-700 font-semibold' : 'text-gray-700 hover:bg-gray-50' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <rect x="3" y="5" width="18" height="16" rx="2" ry="2" />
                    <path d="M16 3v4M8 3v4M3 11h18" />
                </svg>
                <span>Bookings</span>
            </a>

            {{-- Reports --}}
            <details class="group">
                <summary class="flex items-center gap-3 p-2 rounded-lg cursor-pointer text-gray-700 hover:bg-gray-50">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                        <path d="M3 3v18h18" />
                        <path d="M9 17V9M15 17V5" />
                    </svg>
                    <span>Reports</span>
                </summary>

                <div class="mt-2 space-y-1 pl-6">
                    <a href="{{ url('dashboard/reports/properties') }}"
                        class="block py-1 rounded-md text-sm {{ request()->is('dashboard/reports/properties') ? 'text-indigo-700 font-semibold' : 'text-gray-600 hover:text-indigo-700' }}">
                        • Properties Report
                    </a>

                    <a href="{{ url('dashboard/reports/bookings') }}"
                        class="block py-1 rounded-md text-sm {{ request()->is('dashboard/reports/bookings') ? 'text-indigo-700 font-semibold' : 'text-gray-600 hover:text-indigo-700' }}">
                        • Bookings Report
                    </a>
                </div>
            </details>

            {{-- Users --}}
            <a href="{{ url('dashboard/users') }}"
                class="flex items-center gap-3 p-2 rounded-lg transition-colors {{ request()->is('dashboard/users*') ? 'bg-indigo-50 text-indigo-700 font-semibold' : 'text-gray-700 hover:bg-gray-50' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <path d="M17 21v-2a4 4 0 00-4-4H7a4 4 0 00-4 4v2" />
                    <circle cx="9" cy="7" r="4" />
                    <path d="M23 21v-2a4 4 0 00-3-3.87" />
                    <path d="M16 3.13a4 4 0 010 7.75" />
                </svg>
                <span>Users</span>
            </a>
        </nav>
       
   @elserole('employee')

<nav class="space-y-2">

    <a href="{{ route('employee.bookings.my') }}"
       class="flex items-center gap-3 p-2 rounded-lg text-gray-700 hover:bg-gray-50">
        My Bookings
    </a>

    <a href="{{ route('employee.bookings.pending') }}"
       class="flex items-center gap-3 p-2 rounded-lg text-gray-700 hover:bg-gray-50">
        Pending Bookings
    </a>

</nav>

@endrole



        <div class="mt-6 pt-4 border-t text-xs text-gray-500">
            © {{ date('Y') }} RealEstateSys
        </div>
    </div>
</aside>