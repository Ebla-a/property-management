<style>
.sidebar-wrapper {
    position: fixed;
    top: 60px;              
    left: 0;
    width: 260px;
    height: calc(100vh - 60px);
    background: #ffffff;   
    border-right: 1px solid #e5e5e5;
    padding: 18px 14px;
    overflow-y: auto;
}

.sidebar-item {
    display: flex;
    align-items: center;
    gap: 10px;
    width: 100%;
    padding: 10px 10px;
    border-radius: 10px;
    text-decoration: none;
    color: #222;
}

.sidebar-item.active {
    background: #f5f5f5;
    font-weight: 600;
}


.sidebar-item:hover {
    background: #f2f2f2;
}

details summary {
    list-style: none;
}


.sidebar-sub {
    display: block;
    padding: 6px 8px;
    border-radius: 8px;
    color: #444;
    text-decoration: none;
}

.sidebar-sub:hover {
    background: #f5f5f5;
}

.sidebar-sub.active-sub {
    font-weight: 600;
}






</style>

    <aside class="sidebar-wrapper">


    <div class="px-4 py-4 border-b">
        <h2 class="text-lg font-bold tracking-wide">Admin Panel</h2>
        <p class="text-xs text-gray-500">Property Management System</p>
    </div>

    <nav class="flex-1 px-3 py-4 space-y-1">

        <a href="{{ url('dashboard') }}"
           class="sidebar-item {{ request()->is('dashboard') ? 'active' : '' }}">
            <span>🏠</span>
            <span>Dashboard</span>
        </a>

        <a href="{{ url('dashboard/properties') }}"
           class="sidebar-item {{ request()->is('dashboard/properties*') ? 'active' : '' }}">
            <span>🏢</span>
            <span>Properties</span>
        </a>

        <a href="{{ url('dashboard/bookings') }}"
           class="sidebar-item {{ request()->is('dashboard/bookings*') ? 'active' : '' }}">
            <span>🗂️</span>
            <span>Bookings</span>
        </a>

        <details class="group">
            <summary class="sidebar-item cursor-pointer">
                <span>📊</span>
                <span>Reports</span>
            </summary>

            <div class="ml-6 mt-1 space-y-1">
                <a href="{{ url('dashboard/reports/properties') }}"
                   class="sidebar-sub {{ request()->is('dashboard/reports/properties') ? 'active-sub' : '' }}">
                    • Properties Report
                </a>

                <a href="{{ url('dashboard/reports/bookings') }}"
                   class="sidebar-sub {{ request()->is('dashboard/reports/bookings') ? 'active-sub' : '' }}">
                    • Bookings Report
                </a>
            </div>
        </details>

        <a href="{{ url('dashboard/users') }}"
           class="sidebar-item {{ request()->is('dashboard/users*') ? 'active' : '' }}">
            <span>👤</span>
            <span>Users</span>
        </a>

    </nav>

    <div class="px-4 py-3 border-t text-xs text-gray-500">
        © {{ date('Y') }} Property System
    </div>

</aside>
