<aside class="hidden lg:block">
    <div class="fixed top-16 left-0 w-64 h-[calc(100vh-64px)] bg-white border-r shadow-sm overflow-auto px-4 py-6">
        <div class="mb-6">
            <p class="text-xs text-gray-500 mt-1">Property Management System</p>
        </div>

        <nav class="space-y-2">
            {{-- Dashboard --}}
            <a href="{{ url('dashboard') }}"
                class="flex items-center gap-3 p-3 rounded-lg transition-colors {{ request()->is('dashboard') ? 'bg-indigo-50 text-indigo-700 font-semibold' : 'text-gray-700 hover:bg-gray-50' }}">
                <svg xmlns="http://www.w3.org/2000/svg"
                    class="h-7 w-7 flex-shrink-0"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor"
                    stroke-width="1.5">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M3.75 3.75h6.5v6.5h-6.5v-6.5zm0 9.75h6.5v6.5h-6.5v-6.5zm9.75-9.75h6.5v6.5h-6.5v-6.5zm0 9.75h6.5v6.5h-6.5v-6.5z" />
                </svg>

                <span>Home</span>
            </a>
            @role('admin')

            {{-- Properties --}}
            <a href="{{ url('dashboard/properties') }}"
                class="flex items-center gap-3 p-3 rounded-lg transition-colors {{ request()->is('dashboard/properties*') ? 'bg-indigo-50 text-indigo-700 font-semibold' : 'text-gray-700 hover:bg-gray-50' }}">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3.75h.008v.008h-.008v-.008Zm0 3h.008v.008h-.008v-.008Zm0 3h.008v.008h-.008v-.008Z" />
                </svg>

                <span>Properties</span>
            </a>

            {{-- Amenities --}}
            <a href="{{ route('dashboard.amenities.index') }}"
                class="flex items-center gap-3 p-3 rounded-lg transition-colors {{ request()->is('dashboard/amenities*') ? 'bg-indigo-50 text-indigo-700 font-semibold' : 'text-gray-700 hover:bg-gray-50' }}">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904 9 18.75l-.813-2.846a4.5 4.5 0 0 0-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 0 0 3.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 0 0 3.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 0 0-3.09 3.09ZM18.259 8.715 18 9.75l-.259-1.035a3.375 3.375 0 0 0-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 0 0 2.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 0 0 2.456 2.456L21.75 6l-1.035.259a3.375 3.375 0 0 0-2.456 2.456ZM16.894 20.567 16.5 21.75l-.394-1.183a2.25 2.25 0 0 0-1.423-1.423L13.5 18.75l1.183-.394a2.25 2.25 0 0 0 1.423-1.423l.394-1.183.394 1.183a2.25 2.25 0 0 0 1.423 1.423l1.183.394-1.183.394a2.25 2.25 0 0 0-1.423 1.423Z" />
                </svg>

                <span>Amenities</span>
            </a>

            {{-- Bookings --}}
            <a href="{{ url('dashboard/bookings') }}"
                class="flex items-center gap-3 p-3 rounded-lg transition-colors {{ request()->is('dashboard/bookings*') ? 'bg-indigo-50 text-indigo-700 font-semibold' : 'text-gray-700 hover:bg-gray-50' }} ">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <rect x="3" y="5" width="18" height="16" rx="2" ry="2" />
                    <path d="M16 3v4M8 3v4M3 11h18" />
                </svg>
                <span>Bookings</span>
            </a>

            {{-- Reports --}}
            <details class="group">
                <summary class="flex items-center gap-3 p-3 rounded-lg cursor-pointer text-gray-700 hover:bg-gray-50">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
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
            <a href="{{ route('dashboard.admin.employees.index') }}"
                class="flex items-center gap-3 p-3 rounded-lg transition-colors {{ request()->routeIs('dashboard.admin.*') && request()->is('dashboard/users*') ? 'bg-indigo-50 text-indigo-700 font-semibold' : 'text-gray-700 hover:bg-gray-50' }}">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
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
                       <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                    <rect x="3" y="5" width="18" height="16" rx="2" ry="2" />
                    <path d="M16 3v4M8 3v4M3 11h18" />
                </svg>
        Bookings
    </a>

            <a href="{{ route('employee.bookings.pending') }}"
                class="flex items-center gap-3 p-3 rounded-lg text-gray-700 hover:bg-gray-50">
                Pending Bookings
            </a>

        </nav>

        @endrole



        <div class="mt-6 pt-4 border-t text-xs text-gray-500">
            © {{ date('Y') }} RealEstateSys
        </div>
    </div>
</aside>
