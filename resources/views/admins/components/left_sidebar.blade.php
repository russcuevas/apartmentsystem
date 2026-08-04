<aside class="sidebar-aside" id="sidebarAside">
    <!-- Sidebar Header with Logo -->
    <div class="sidebar-header">
        <a href="{{ route('admin.dashboard.page') }}" class="sidebar-logo">
            <div class="logo-icon">
                <!-- Custom SVG Apartment Building Icon -->
                <svg viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M19 2H9c-1.1 0-2 .9-2 2v3H3c-1.1 0-2 .9-2 2v11c0 1.1.9 2 2 2h18c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zM5 20H3V9h2v11zm4 0H7v-3h2v3zm0-5H7v-3h2v3zm0-5H7V7h2v3zm10 10h-8V4h8v16zm-2-12h-4v2h4V8zm0 4h-4v2h4v-2zm0 4h-4v2h4v-2z" />
                </svg>
            </div>
            <span class="logo-text">LMS <span>Apartment</span></span>
        </a>
    </div>

    <!-- Sidebar Main Menu Options -->
    <div class="sidebar-content">
        <p class="menu-label">Main Navigation</p>
        <ul class="sidebar-menu">
            <!-- Dashboard Link -->
            <li>
                <a href="{{ route('admin.dashboard.page') }}"
                    class="menu-item-link {{ request()->routeIs('admin.dashboard.page') ? 'active' : '' }}">
                    <span class="menu-item-left">
                        <!-- Dashboard Grid Icon -->
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z">
                            </path>
                        </svg>
                        Dashboard
                    </span>
                </a>
            </li>

            <!-- All Tenants Link -->
            {{-- <li>
                <a href="{{ route('admin.tenants.page') }}"
                    class="menu-item-link {{ request()->routeIs('admin.tenants.page') && !request()->has('location_id') ? 'active' : '' }}">
                    <span class="menu-item-left">
                        <!-- User / Tenants Icon -->
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z">
                            </path>
                        </svg>
                        Tenants
                    </span>
                </a>
            </li> --}}

            <!-- Locations Dropdown Option -->
            <li class="menu-item-dropdown {{ request()->is('admin/locations/*') || request()->has('location_id') ? 'open' : '' }}"
                id="locationsMenuDropdown">
                <a class="menu-item-link" id="locationsToggle">
                    <span class="menu-item-left">
                        <!-- Location/Map Pin Icon -->
                        <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                            </path>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z">
                            </path>
                        </svg>
                        Locations
                    </span>
                    <!-- Chevron Dropdown Indicator -->
                    <svg class="chevron-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"></path>
                    </svg>
                </a>

                <!-- Submenu Locations -->
                <ul class="dropdown-submenu">
                    @php
                        $backendLocations = \App\Models\Locations::all();
                    @endphp
                    @forelse ($backendLocations as $loc)
                        <li
                            class="location-sub-dropdown {{ request()->is('admin/locations/' . $loc->id . '*') || (request()->routeIs('admin.tenants.page', 'admin.billings.index') && request('location_id') == $loc->id) ? 'open' : '' }}">
                            <a href="javascript:void(0)" class="location-sub-toggle">
                                <span>{{ $loc->location_name }}</span>
                                <svg class="chevron-icon-sm" fill="none" stroke="currentColor" stroke-width="2"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </a>
                            <ul class="location-nested-menu">
                                <li>
                                    <a href="{{ route('admin.locations.page', $loc->id) }}"
                                        class="nested-link {{ request()->is('admin/locations/' . $loc->id) ? 'active' : '' }}">
                                        Dashboard
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('admin.tenants.page', ['location_id' => $loc->id]) }}"
                                        class="nested-link {{ request()->routeIs('admin.tenants.page') && request('location_id') == $loc->id ? 'active' : '' }}">
                                        Tenants
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('admin.billings.index', ['location_id' => $loc->id]) }}"
                                        class="nested-link {{ request()->routeIs('admin.billings.index') && request('location_id') == $loc->id ? 'active' : '' }}">
                                        Billings
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('admin.locations.page', $loc->id) }}#tenant-payments"
                                        class="nested-link">
                                        Payments
                                    </a>
                                </li>
                            </ul>
                        </li>
                    @empty
                        <li><a href="#" class="submenu-link">No Locations</a></li>
                    @endforelse
                </ul>
            </li>
        </ul>
    </div>

    <!-- Sidebar Footer -->
    <div class="sidebar-footer">
        <div class="footer-avatar">{{ strtoupper(substr(Auth::guard('admin')->user()->fullname ?? 'A', 0, 1)) }}</div>
        <div class="footer-user-info">
            <span class="footer-user-name">{{ Auth::guard('admin')->user()->fullname ?? 'Admin User' }}</span>
            <span class="footer-user-role">Administrator</span>
        </div>
    </div>
</aside>
