<header class="fixed top-0 inset-x-0 z-40 bg-white/95 backdrop-blur-sm border-b border-gray-100 shadow-sm" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
    <div class="mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between gap-4">

        <div class="flex items-center gap-3">
            <a href="{{ url('dashboard') }}" class="flex items-center gap-3">
                <div class="hidden sm:flex flex-col leading-tight">
                    <span class="text-2xl font-bold text-indigo-600">RealEstate</span>
                </div>
                <img src="/logo.png" alt="logo" class="h-10 w-10 object-contain">
            </a>
        </div>

        <div class="flex-1 hidden md:flex items-center justify-center"></div>

        <div class="flex items-center gap-3">
            <div class="flex items-center gap-2 {{ app()->getLocale() == 'ar' ? 'border-l pl-3' : 'border-r pr-3' }} border-gray-100">
                {{-- change language --}}
                <div class="relative ml-2" x-data="{ open: false }">
                    <button @click="open = !open" 
                            class="flex items-center gap-2 px-3 py-2 text-sm font-medium text-gray-700 bg-gray-50 border border-gray-100 rounded-lg hover:bg-indigo-50 transition focus:outline-none">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064" />
                        </svg>
                        <span>{{ app()->getLocale() == 'ar' ? 'العربية' : 'English' }}</span>
                        <svg class="h-3 w-3 text-gray-400 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>

                    <div x-show="open" 
                         @click.away="open = false"
                         x-transition:enter="transition ease-out duration-100"
                         x-transition:enter-start="transform opacity-0 scale-95"
                         x-transition:enter-end="transform opacity-100 scale-100"
                         class="absolute {{ app()->getLocale() == 'ar' ? 'left-0' : 'right-0' }} mt-2 w-32 bg-white border border-gray-100 rounded-lg shadow-xl z-[60] overflow-hidden">
                        
                       
                        <a href="{{ url('lang/ar') }}" 
                           class="block px-4 py-2 text-sm text-gray-700 hover:bg-indigo-50 transition {{ app()->getLocale() == 'ar' ? 'bg-indigo-50 font-bold' : '' }}">
                           {{ __('messages.header.arabic') }}
                        </a>
                        <a href="{{ url('lang/en') }}" 
                           class="block px-4 py-2 text-sm text-gray-700 hover:bg-indigo-50 transition {{ app()->getLocale() == 'en' ? 'bg-indigo-50 font-bold' : '' }}">
                           {{ __('messages.header.english') }}
                        </a>
                    </div>
                </div>
            </div>

            @role('admin')
            <a href="{{ url('dashboard/properties/create') }}"
               class="hidden md:inline-flex items-center gap-2 px-3 py-2 rounded-full bg-gradient-to-r from-indigo-600 to-indigo-500 text-white text-sm font-semibold shadow hover:scale-[1.02] transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path d="M12 5v14M5 12h14" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
                {{ __('messages.header.new_property') }}
            </a>
            @endrole

            <div class="relative" x-data="">
                <button id="userToggle" aria-haspopup="true" aria-expanded="false"
                        class="flex items-center gap-3 p-2 rounded-full hover:bg-indigo-50 transition focus:outline-none">
                    <div class="h-10 w-10 rounded-full bg-indigo-50 flex items-center justify-center text-indigo-600 font-semibold">
                        {{ strtoupper(substr(auth()->user()?->name ?? 'G', 0, 1)) }}
                    </div>

                    <div class="hidden sm:flex flex-col {{ app()->getLocale() == 'ar' ? 'text-right' : 'text-left' }}">
                        <span class="text-sm font-medium text-gray-700">{{ auth()->user()?->name ?? 'Guest' }}</span>
                    </div>

                    <svg id="userCaret" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-gray-400 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>

                <div id="userMenu" class="hidden absolute {{ app()->getLocale() == 'ar' ? 'left-0' : 'right-0' }} mt-2 w-56 bg-white border border-gray-100 rounded-lg shadow-lg overflow-hidden z-50">
                    <div class="px-4 py-3">
                        <div class="flex {{ app()->getLocale() == 'ar' ? 'justify-start' : 'justify-end' }} mb-1">
                            @role('admin')
                                <span class="px-2 py-0.5 text-xs rounded-full bg-indigo-100 text-indigo-700 font-semibold">{{ __('messages.header.admin_badge') }}</span>
                            @elserole('employee')
                                <span class="px-2 py-0.5 text-xs rounded-full bg-indigo-50 text-indigo-700 font-medium">{{ __('messages.header.employee_badge') }}</span>
                            @endrole
                        </div>
                        <p class="text-sm font-medium text-gray-800">{{ auth()->user()?->name ?? 'Guest' }}</p>
                        <p class="text-xs text-gray-500">{{ auth()->user()?->email ?? 'guest@example.com' }}</p>
                    </div>

                    <div class="border-t border-gray-100"></div>

                    @role('admin')
                        <a href="{{ url('dashboard/properties') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 text-start">{{ __('messages.sidebar.properties') }}</a>
                    @endrole

@role('admin')
    <a href="{{ url('dashboard/bookings') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">Bookings</a>
@elserole('employee')
    <a href="{{ url('dashboard/my-own-bookings') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">Bookings</a>
@endrole

                    <div class="border-t border-gray-100"></div>

                    <button
                        type="button"
                        onclick="window.dispatchEvent(new CustomEvent('open-modal', { detail: 'logout-modal' }))"
                        class="w-full text-center px-4 py-2 text-red-600 hover:bg-gray-50">
                        {{ __('messages.sidebar.logout') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</header>

<div class="h-16"></div>

<script>
(function(){
    const userToggle = document.getElementById('userToggle');
    const userMenu = document.getElementById('userMenu');
    const userCaret = document.getElementById('userCaret');
    let menuOpen = false;

    function closeMenu(){ menuOpen=false; userMenu.classList.add('hidden'); userCaret.classList.remove('rotate-180'); userToggle.setAttribute('aria-expanded','false'); }
    function openMenu(){ menuOpen=true; userMenu.classList.remove('hidden'); userCaret.classList.add('rotate-180'); userToggle.setAttribute('aria-expanded','true'); }

    document.addEventListener('click', function(e){
        if(!userToggle.contains(e.target) && !userMenu.contains(e.target)) closeMenu();
    });

    userToggle.addEventListener('click', function(e){
        e.stopPropagation();
        menuOpen ? closeMenu() : openMenu();
    });
})();
</script>