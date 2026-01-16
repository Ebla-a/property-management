@php
    $isRtl = app()->getLocale() == 'ar';

    $linkBase = 'flex items-center gap-3 p-3 rounded-lg transition-colors focus:outline-none focus-visible:ring-2 focus-visible:ring-indigo-500 focus-visible:ring-offset-2 focus-visible:ring-offset-white';
    $linkDirectional = $isRtl ? 'flex-row text-right' : 'flex-row text-left';
    $subListPadding = $isRtl ? 'pr-12' : 'pl-12';
    $asidePosition = $isRtl ? 'right-0' : 'left-0';
    $asideBorder   = $isRtl ? 'border-l' : 'border-r';
@endphp

<aside class="hidden lg:block">
    <div
        class="fixed top-16 {{ $asidePosition }} w-64 h-[calc(100vh-64px)] bg-white {{ $asideBorder }} shadow-sm overflow-hidden px-4 py-6"
        dir="{{ $isRtl ? 'rtl' : 'ltr' }}">

        <div class="flex flex-col h-full">
            <div class="mb-4 px-3">
                <p class="text-sm text-gray-500 mt-1">{{ __('messages.sidebar.system_name') }}</p>
            </div>

            <div class="flex-1 overflow-auto {{ $isRtl ? 'pl-1' : 'pr-1' }}">
                @role('admin')
                <nav class="space-y-2">
                    <a href="{{ url('dashboard') }}"
                       class="{{ $linkBase }} {{ $linkDirectional }} {{ request()->is('dashboard') ? 'bg-indigo-50 text-indigo-700 font-semibold' : 'text-gray-700 hover:bg-gray-50' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 3.75h6.5v6.5h-6.5v-6.5zm0 9.75h6.5v6.5h-6.5v-6.5zm9.75-9.75h6.5v6.5h-6.5v-6.5zm0 9.75h6.5v6.5h-6.5v-6.5z" />
                        </svg>
                        <span class="text-base font-medium">{{ __('messages.sidebar.home') }}</span>
                    </a>

                    <a href="{{ url('dashboard/properties') }}"
                       class="{{ $linkBase }} {{ $linkDirectional }} {{ request()->is('dashboard/properties*') ? 'bg-indigo-50 text-indigo-700 font-semibold' : 'text-gray-700 hover:bg-gray-50' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-6 w-6 flex-shrink-0">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M6.75 21v-3.375c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21M3 3h12m-.75 4.5H21m-3.75 3.75h.008v.008h-.008v-.008Zm0 3h.008v.008h-.008v-.008Zm0 3h.008v.008h-.008v-.008Z" />
                        </svg>
                        <span class="text-base font-medium">{{ __('messages.sidebar.properties') }}</span>
                    </a>

                    <a href="{{ route('dashboard.amenities.index') }}"
                       class="{{ $linkBase }} {{ $linkDirectional }} {{ request()->is('dashboard/amenities*') ? 'bg-indigo-50 text-indigo-700 font-semibold' : 'text-gray-700 hover:bg-gray-50' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-6 w-6 flex-shrink-0">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.813 15.904 9 18.75l-.813-2.846a4.5 4.5 0 0 0-3.09-3.09L2.25 12l2.846-.813a4.5 4.5 0 0 0 3.09-3.09L9 5.25l.813 2.846a4.5 4.5 0 0 0 3.09 3.09L15.75 12l-2.846.813a4.5 4.5 0 0 0-3.09 3.09ZM18.259 8.715 18 9.75l-.259-1.035a3.375 3.375 0 0 0-2.455-2.456L14.25 6l1.036-.259a3.375 3.375 0 0 0 2.455-2.456L18 2.25l.259 1.035a3.375 3.375 0 0 0 2.456 2.456L21.75 6l-1.035.259a3.375 3.375 0 0 0-2.456 2.456ZM16.894 20.567 16.5 21.75l-.394-1.183a2.25 2.25 0 0 0-1.423-1.423L13.5 18.75l1.183-.394a2.25 2.25 0 0 0 1.423-1.423l.394-1.183.394 1.183a2.25 2.25 0 0 0 1.423 1.423l1.183.394-1.183.394a2.25 2.25 0 0 0-1.423 1.423Z" />
                        </svg>
                        <span class="text-base font-medium">{{ __('messages.sidebar.amenities') }}</span>
                    </a>

                    <a href="{{ url('dashboard/bookings') }}"
                       class="{{ $linkBase }} {{ $linkDirectional }} {{ request()->is('dashboard/bookings*') ? 'bg-indigo-50 text-indigo-700 font-semibold' : 'text-gray-700 hover:bg-gray-50' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                            <rect x="3" y="5" width="18" height="16" rx="2" ry="2" />
                            <path d="M16 3v4M8 3v4M3 11h18" />
                        </svg>
                        <span class="text-base font-medium">{{ __('messages.sidebar.bookings') }}</span>
                    </a>

                    <details class="group">
                        <summary class="{{ $linkBase }} {{ $linkDirectional }} cursor-pointer text-gray-700 hover:bg-gray-50 list-none">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                                <path d="M3 3v18h18" />
                                <path d="M9 17V9M15 17V5" />
                            </svg>
                            <span class="text-base font-medium flex-1">{{ __('messages.sidebar.reports') }}</span>
                            <svg class="w-4 h-4 transition-transform group-open:rotate-180" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path d="M19 9l-7 7-7-7" /></svg>
                        </summary>

                        <div class="mt-1 space-y-1 {{ $subListPadding }}">
                            <a href="{{ url('dashboard/reports/properties') }}"
                               class="block py-2 rounded-md text-sm {{ request()->is('dashboard/reports/properties') ? 'text-indigo-700 font-semibold' : 'text-gray-600 hover:text-indigo-700' }}">
                                • {{ __('messages.sidebar.properties_report') }}
                            </a>

                            <a href="{{ url('dashboard/reports/bookings') }}"
                               class="block py-2 rounded-md text-sm {{ request()->is('dashboard/reports/bookings') ? 'text-indigo-700 font-semibold' : 'text-gray-600 hover:text-indigo-700' }}">
                                • {{ __('messages.sidebar.bookings_report') }}
                            </a>
                        </div>
                    </details>

                    <a href="{{ route('dashboard.admin.employees.index') }}"
                       class="{{ $linkBase }} {{ $linkDirectional }} {{ (request()->routeIs('dashboard.admin.*') && request()->is('dashboard/users*')) ? 'bg-indigo-50 text-indigo-700 font-semibold' : 'text-gray-700 hover:bg-gray-50' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 flex-shrink-0" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                            <path d="M17 21v-2a4 4 0 00-4-4H7a4 4 0 00-4 4v2" />
                            <circle cx="9" cy="7" r="4" />
                            <path d="M23 21v-2a4 4 0 00-3-3.87" />
                            <path d="M16 3.13a4 4 0 010 7.75" />
                        </svg>
                        <span class="text-base font-medium">{{ __('messages.sidebar.users') }}</span>
                    </a>
                </nav>
                @elserole('employee')
                <nav class="space-y-2">
                    <a href="{{ route('employee.dashboard.employee') }}"
                       class="{{ $linkBase }} {{ $linkDirectional }} {{ request()->is('dashboard') ? 'bg-indigo-50 text-indigo-700 font-semibold' : 'text-gray-700 hover:bg-gray-50' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 3.75h6.5v6.5h-6.5v-6.5zm0 9.75h6.5v6.5h-6.5v-6.5zm9.75-9.75h6.5v6.5h-6.5v-6.5zm0 9.75h6.5v6.5h-6.5v-6.5z" />
                        </svg>
                        <span class="text-base font-medium">{{ __('messages.sidebar.home') }}</span>
                    </a>

                    <a href="{{ route('employee.bookings.my') }}"
                       class="{{ $linkBase }} {{ $linkDirectional }} {{ request()->is('dashboard/bookings/my') ? 'bg-indigo-50 text-indigo-700 font-semibold' : 'text-gray-700 hover:bg-gray-50' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <rect x="3" y="5" width="18" height="16" rx="2" ry="2"/>
                            <path d="M16 3v4M8 3v4M3 11h18"/>
                        </svg>
                        <span class="text-base font-medium">{{ __('messages.sidebar.my_bookings') }}</span>
                    </a>

                    <a href="{{ route('employee.bookings.pending') }}"
                       class="{{ $linkBase }} {{ $linkDirectional }} {{ request()->is('dashboard/bookings/pending') ? 'bg-indigo-50 text-indigo-700 font-semibold' : 'text-gray-700 hover:bg-gray-50' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3"/>
                            <circle cx="12" cy="12" r="9"/>
                        </svg>
                        <span class="text-base font-medium">{{ __('messages.sidebar.pending_bookings') }}</span>
                    </a>
                </nav>
                @endrole
            </div>

            <div class="mt-4 pt-4 border-t">
                <button
                    type="button"
                    onclick="window.dispatchEvent(new CustomEvent('open-modal', { detail: 'logout-modal' }))"
                    class="w-full flex {{ $linkDirectional }} items-center gap-3 px-3 py-3 rounded-lg hover:bg-red-50 text-red-600 transition-colors group">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 {{ $isRtl ? 'rotate-180' : '' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9" />
                    </svg>
                    <span class="text-lg font-medium">{{ __('messages.sidebar.logout') }}</span>
                </button>

                <p class="mt-3 text-xs text-gray-400 text-center">
                    © {{ date('Y') }} RealEstateSys
                </p>
            </div>
        </div>
    </div>
</aside>