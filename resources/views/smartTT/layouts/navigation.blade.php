<ul class="sidebar-nav" data-coreui="navigation" data-simplebar>
    <li class="nav-item">
        <a class="nav-link {{ request()->is('home*') ? 'active' : '' }}" href="{{ route('home') }}">
            <svg class="nav-icon">
                <use xlink:href="{{ asset('icons/coreui.svg#cil-home') }}"></use>
            </svg>
            {{ __('Dashboard') }}
        </a>
    </li>

    @can('Access Tour')
        <li class="nav-item">
            <a class="nav-link {{ request()->is('tours*') ? 'active' : '' }}" href="{{ route('tours.index') }}">
                <svg class="nav-icon">
                    <use xlink:href="{{ asset('icons/coreui.svg#cil-beach-access') }}"></use>
                </svg>
                {{ __('Tour Management') }}
            </a>
        </li>
    @endcan

    @can('Access Package')
        <li class="nav-item">
            <a class="nav-link {{ request()->is('package*') ? 'active' : '' }}" href="{{ route('packages.index') }}">
                <svg class="nav-icon">
                    <use xlink:href="{{ asset('icons/coreui.svg#cil-flag-alt') }}"></use>
                </svg>
                {{ __('Package Management') }}
            </a>
        </li>
    @endcan

    @can('Access Flight')
        <li class="nav-item">
            <a class="nav-link {{ request()->is('flights*') ? 'active' : '' }}" href="{{ route('flights.index') }}">
                <svg class="nav-icon">
                    <use xlink:href="{{ asset('icons/coreui.svg#cil-airplane-mode') }}"></use>
                </svg>
                {{ __('Flight Management') }}
            </a>
        </li>
    @endcan

    @can('Access Booking')
        <li class="nav-item">
            <a class="nav-link {{ request()->is('bookings*') ? 'active' : '' }}" href="{{ route('bookings.index') }}">
                <svg class="nav-icon">
                    <use xlink:href="{{ asset('icons/coreui.svg#cil-dollar') }}"></use>
                </svg>
                {{ __('Booking Management') }}
            </a>
        </li>
    @endcan

    @can('Access User')
        <li class="nav-item">
            <a class="nav-link {{ request()->is('users*') ? 'active' : '' }}" href="{{ route('users.index') }}">
                <svg class="nav-icon">
                    <use xlink:href="{{ asset('icons/coreui.svg#cil-user') }}"></use>
                </svg>
                {{ __('Users') }}
            </a>
        </li>
    @endcan

    @can('Access Role')
        <li class="nav-item">
            <a class="nav-link {{ request()->is('roles/*') ? 'active' : '' }}" href="{{ route('roles.index') }}">
                <svg class="nav-icon">
                    <use xlink:href="{{ asset('icons/coreui.svg#cil-group') }}"></use>
                </svg>
                {{ __('Role Management') }}
            </a>
        </li>
    @endcan

    @can('Access Setting')
        <li class="nav-item">
            <a class="nav-link {{ request()->is('settings/*') ? 'active' : '' }}"
                href="{{ route('settings.index') }}">
                <svg class="nav-icon">
                    <use xlink:href="{{ asset('icons/coreui.svg#cil-settings') }}"></use>
                </svg>
                {{ __('Settings') }}
            </a>
        </li>
    @endcan

    @can('Access Report')
        <li class="nav-group" aria-expanded="false">
            <a class="nav-link nav-group-toggle" href="#">
                <svg class="nav-icon">
                    <use xlink:href="{{ asset('icons/coreui.svg#cil-book') }}"></use>
                </svg>
                {{ __('Reports') }}
            </a>
            <ul class="nav-group-items" style="height: 0px;">
                <li class="nav-item">
                    <a class="nav-link" href="{{ route('reports.index', 'sales') }}" target="_top">
                        <svg class="nav-icon">
                            <use xlink:href="{{ asset('icons/coreui.svg#cil-money') }}"></use>
                        </svg>
                        {{ __('Sales') }}
                    </a>
                </li>
            </ul>
        </li>
    @endcan
</ul>
