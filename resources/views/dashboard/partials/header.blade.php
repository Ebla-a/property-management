<header class="fixed top-0 inset-x-0 z-40 bg-white/95 backdrop-blur-sm border-b border-gray-100 shadow-sm"
    dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
    <div class="mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">

        {{-- Left Section: Logo --}}
        <div class="flex items-center gap-3">
            <a href="{{ url('dashboard') }}" class="flex items-center gap-3">
                <div class="hidden sm:flex flex-col leading-tight">
                    <span class="text-2xl font-bold text-indigo-600">RealEstate</span>
                    <span class="text-xs text-gray-500">Dashboard</span>
                </div>
                <img src="/logo.png" alt="logo" class="h-10 w-10 object-contain">
            </a>
        </div>

        {{-- Center Section --}}
        <div class="flex-1 hidden md:flex items-center justify-center"></div>

        {{-- Right Section --}}
        <div class="flex items-center gap-3">

            {{-- Notifications Dropdown --}}
            <div class="relative" x-data="{
                    open: false,
                    left: '0px',
                    top: '0px',
                    calculate() {
                        const btn = this.$refs.btn;
                        const panel = this.$refs.panel;
                        if (!btn || !panel) return;
                        const btnRect = btn.getBoundingClientRect();
                        const panelWidth = panel.offsetWidth;
                        const margin = 12;
                        let left = btnRect.left + (btnRect.width / 2) - (panelWidth / 2);
                        const maxLeft = window.innerWidth - panelWidth - margin;
                        const minLeft = margin;
                        if (left < minLeft) left = minLeft;
                        if (left > maxLeft) left = maxLeft;
                        const top = btnRect.bottom + 12;
                        this.left = left + 'px';
                        this.top = top + 'px';
                    },
                    initListeners() {
                        this._resizeHandler = () => this.open && this.calculate();
                        window.addEventListener('resize', this._resizeHandler);
                        window.addEventListener('scroll', this._resizeHandler, true);
                    }
                }" 
                x-init="initListeners()" 
                x-on:keydown.escape.window="open = false" 
                x-cloak 
                @click.outside="open = false">
                
                <button x-ref="btn" @click="open = !open; if(open){ $nextTick(()=> calculate()) }"
                    class="relative p-2 rounded-full hover:bg-indigo-50 transition focus:outline-none">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0a3 3 0 11-6 0h6z" />
                    </svg>

                    @php
                        $userNotifications = auth()->user()->unreadNotifications;
                        if(auth()->user()->hasRole('employee')) {
                            $userNotifications = $userNotifications->where('data.type', 'booking');
                        }
                    @endphp

                    @if($userNotifications->count())
                        <span class="absolute -top-1 -right-1 bg-red-600 text-white text-[10px] font-bold px-1.5 rounded-full animate-pulse">
                            {{ $userNotifications->count() }}
                        </span>
                    @endif
                </button>

                <div x-ref="panel" x-show="open" x-transition style="position: fixed; left: 0; top: 0;" :style="{ left: left, top: top }"
                    class="w-96 max-w-[90vw] bg-white border border-gray-100 rounded-2xl shadow-2xl z-50 overflow-hidden">
                    <div class="px-6 py-3 font-semibold text-gray-700 border-b border-gray-100 bg-indigo-50 flex items-center justify-between">
                        <span>Notifications</span>
                        @if($userNotifications->count())
                            <form method="POST" action="{{ route('notifications.read') }}">
                                @csrf
                                <button type="submit" class="text-indigo-600 text-xs hover:underline">Mark all as read</button>
                            </form>
                        @endif
                    </div>
                    <div class="max-h-80 overflow-y-auto divide-y divide-gray-100 bg-white">
                        @forelse($userNotifications->take(6) as $notification)
                            <div class="px-4 py-3 flex items-start gap-3 hover:bg-indigo-50 transition cursor-pointer group">
                                <span class="h-3 w-3 mt-1 rounded-full bg-indigo-600"></span>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium text-gray-800 truncate group-hover:underline">
                                        {{ $notification->data['message'] ?? 'No message' }}
                                    </p>
                                    <div class="mt-1 flex items-center gap-2 text-xs text-gray-500">
                                        <span>by <span class="text-gray-700 font-medium">{{ $notification->data['by'] ?? 'System' }}</span></span>
                                        <span>• {{ $notification->created_at->diffForHumans() }}</span>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="px-6 py-8 text-center text-sm text-gray-500">No new notifications</div>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- Language Switcher (Clean Version) --}}
            <div class="flex items-center gap-2 {{ app()->getLocale() == 'ar' ? 'border-l pl-3' : 'border-r pr-3' }} border-gray-100">
                <div class="relative" x-data="{ open: false }">
                    <button @click="open = !open"
                        class="flex items-center gap-2 px-3 py-2 text-sm font-medium text-gray-700 bg-gray-50 border border-gray-100 rounded-lg hover:bg-indigo-50 transition focus:outline-none">
                        <span>{{ app()->getLocale() == 'ar' ? __('messages.header.arabic') : __('messages.header.english') }}</span>
                        <svg class="h-3 w-3 text-gray-400 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    <div x-show="open" @click.away="open = false" x-transition
                        class="absolute {{ app()->getLocale() == 'ar' ? 'left-0' : 'right-0' }} mt-2 w-32 bg-white border border-gray-100 rounded-lg shadow-xl z-[60] overflow-hidden">
                        <a href="{{ url('lang/ar') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-indigo-50 transition {{ app()->getLocale() == 'ar' ? 'bg-indigo-50 font-bold' : '' }}">
                            {{ __('messages.header.arabic') }}
                        </a>
                        <a href="{{ url('lang/en') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-indigo-50 transition {{ app()->getLocale() == 'en' ? 'bg-indigo-50 font-bold' : '' }}">
                            {{ __('messages.header.english') }}
                        </a>
                    </div>
                </div>
            </div>

            {{-- New Property Button --}}
            @role('admin')
                <a href="{{ url('dashboard/properties/create') }}"
                    class="hidden md:inline-flex items-center gap-2 px-3 py-2 rounded-full bg-gradient-to-r from-indigo-600 to-indigo-500 text-white text-sm font-semibold shadow hover:scale-[1.02] transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path d="M12 5v14M5 12h14" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                    {{ __('messages.header.new_property') }}
                </a>
            @endrole

            {{-- User Menu (Merged & Fixed) --}}
            <div class="relative" x-data="{ open: false }">
                <button @click="open = !open" class="flex items-center gap-3 p-2 rounded-full hover:bg-indigo-50 transition focus:outline-none">
                    <div class="h-10 w-10 rounded-full bg-indigo-50 flex items-center justify-center text-indigo-600 font-semibold">
                        {{ strtoupper(substr(auth()->user()?->name ?? 'G', 0, 1)) }}
                    </div>
                    <div class="hidden sm:flex flex-col {{ app()->getLocale() == 'ar' ? 'text-right' : 'text-left' }}">
                        <span class="text-sm font-medium text-gray-700">{{ auth()->user()?->name ?? __('messages.header.guest') }}</span>
                    </div>
                    <svg class="h-4 w-4 text-gray-400 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>

                <div x-show="open" @click.away="open = false" x-transition
                    class="absolute {{ app()->getLocale() == 'ar' ? 'left-0' : 'right-0' }} mt-2 w-56 bg-white border border-gray-100 rounded-lg shadow-lg overflow-hidden z-50">
                    <div class="px-4 py-3">
                        <div class="flex {{ app()->getLocale() == 'ar' ? 'justify-start' : 'justify-end' }} mb-1">
                            @role('admin')
                                <span class="px-2 py-0.5 text-xs rounded-full bg-indigo-100 text-indigo-700 font-semibold">{{ __('messages.header.admin_badge') }}</span>
                            @elserole('employee')
                                <span class="px-2 py-0.5 text-xs rounded-full bg-indigo-50 text-indigo-700 font-medium">{{ __('messages.header.employee_badge') }}</span>
                            @endrole
                        </div>
                        <p class="text-sm font-medium text-gray-800">{{ auth()->user()?->name }}</p>
                        <p class="text-xs text-gray-500">{{ auth()->user()?->email }}</p>
                    </div>

                    <div class="border-t border-gray-100"></div>

                    @role('admin')
                        <a href="{{ url('dashboard/properties') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 text-start">{{ __('messages.sidebar.properties') }}</a>
                        <a href="{{ url('dashboard/bookings') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 text-start">{{ __('messages.sidebar.bookings') }}</a>
                    @elserole('employee')
                        <a href="{{ url('dashboard/my-own-bookings') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 text-start">{{ __('messages.sidebar.my_bookings') }}</a>
                    @endrole

                    <div class="border-t border-gray-100"></div>

                    <button type="button" @click="window.dispatchEvent(new CustomEvent('open-modal', { detail: 'logout-modal' }))"
                        class="w-full {{ app()->getLocale() == 'ar' ? 'text-right' : 'text-left' }} px-4 py-2 text-sm text-red-600 hover:bg-gray-50">
                        {{ __('messages.sidebar.logout') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</header>

{{-- Logout Modal --}}
<div x-data="{ open: false }" x-show="open" @open-modal.window="if($event.detail == 'logout-modal') open = true"
     class="fixed inset-0 z-[100] overflow-y-auto" style="display: none;">
    <div class="flex items-center justify-center min-h-screen px-4 text-center">
        <div class="fixed inset-0 bg-gray-500 opacity-75"></div>
        <div class="inline-block bg-white rounded-2xl overflow-hidden shadow-xl transform transition-all sm:max-w-md sm:w-full p-6 relative z-[110]">
            <h3 class="text-xl font-bold text-gray-900 mb-2">{{ __('messages.logout_modal.title') }}</h3>
            <p class="text-sm text-gray-500 mb-6">{{ __('messages.logout_modal.message') }}</p>
            <div class="flex gap-3 justify-center">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="bg-indigo-600 text-white px-8 py-2 rounded-xl font-medium hover:bg-indigo-700 transition">
                        {{ __('messages.logout_modal.confirm') }}
                    </button>
                </form>
                <button @click="open = false" class="bg-gray-100 text-gray-700 px-8 py-2 rounded-xl font-medium hover:bg-gray-200 transition">
                    {{ __('messages.logout_modal.cancel') }}
                </button>
            </div>
        </div>
    </div>
</div>

<div class="h-16"></div>