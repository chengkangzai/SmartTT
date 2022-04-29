<ul class="sidebar-nav" data-coreui="navigation" data-simplebar>
    <li class="nav-item">
        <a class="nav-link {{ request()->is('home*') ? 'active' : '' }}" href="{{ route('home') }}">
            <svg class="nav-icon">
                <use xlink:href="{{ asset('icons/coreui.svg#cil-speedometer') }}"></use>
            </svg>
            {{ __('Dashboard') }}
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link {{ request()->is('tours*') ? 'active' : '' }}" href="{{ route('tours.index') }}">
            <svg class="nav-icon">
                <use xlink:href="{{ asset('icons/coreui.svg#cil-book') }}"></use>
            </svg>
            {{ __('Tour Management') }}
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link {{ request()->is('packages*') ? 'active' : '' }}" href="{{ route('packages.index') }}">
            <svg class="nav-icon">
                <use xlink:href="{{ asset('icons/coreui.svg#cil-flag-alt') }}"></use>
            </svg>
            {{ __('Package Management') }}
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link {{ request()->is('flights*') ? 'active' : '' }}" href="{{ route('flights.index') }}">
            <svg class="nav-icon">
                <use xlink:href="{{ asset('icons/coreui.svg#cil-airplane-mode') }}"></use>
            </svg>
            {{ __('Flight Management') }}
        </a>
    </li>

    <li class="nav-item">
        <a class="nav-link {{ request()->is('bookings*') ? 'active' : '' }}" href="{{ route('bookings.index') }}">
            <svg class="nav-icon">
                <use xlink:href="{{ asset('icons/coreui.svg#cil-dollar') }}"></use>
            </svg>
            {{ __('Booking Management') }}
        </a>
    </li>


    @role('Super Admin')
        <li class="nav-item">
            <a class="nav-link {{ request()->is('users*') ? 'active' : '' }}" href="{{ route('users.index') }}">
                <svg class="nav-icon">
                    <use xlink:href="{{ asset('icons/coreui.svg#cil-user') }}"></use>
                </svg>
                {{ __('Users') }}
            </a>
        </li>

        <li class="nav-item">
            <a class="nav-link {{ request()->is('roles/*') ? 'active' : '' }}" href="{{ route('roles.index') }}">
                <svg class="nav-icon">
                    <use xlink:href="{{ asset('icons/coreui.svg#cil-group') }}"></use>
                </svg>
                {{ __('Role Management') }}
            </a>
        </li>
    @endrole

{{--    @role('Super Admin|Manager')--}}
{{--    <li class="nav-item">--}}
{{--        <a class="nav-link {{ request()->is('roles/*') ? 'active' : '' }}" href="{{ route('roles.index') }}">--}}
{{--            <svg class="nav-icon">--}}
{{--                <use xlink:href="{{ asset('icons/coreui.svg#cil-group') }}"></use>--}}
{{--            </svg>--}}
{{--            {{ __('Role Management') }}--}}
{{--        </a>--}}
{{--    </li>--}}
{{--    @endrole--}}


    <li class="nav-item">
        <a class="nav-link {{ request()->is('about') ? 'active' : '' }}" href="{{ route('about') }}">
            <svg class="nav-icon">
                <use xlink:href="{{ asset('icons/coreui.svg#cil-fire') }}"></use>
            </svg>
            {{ __('About us') }}
        </a>
    </li>

    {{-- <li class="nav-group" aria-expanded="false"> --}}
    {{-- <a class="nav-link nav-group-toggle" href="#"> --}}
    {{-- <svg class="nav-icon"> --}}
    {{-- <use xlink:href="{{ asset('icons/coreui.svg#cil-star') }}"></use> --}}
    {{-- </svg> --}}
    {{-- Two-level menu --}}
    {{-- </a> --}}
    {{-- <ul class="nav-group-items" style="height: 0px;"> --}}
    {{-- <li class="nav-item"> --}}
    {{-- <a class="nav-link" href="#" target="_top"> --}}
    {{-- <svg class="nav-icon"> --}}
    {{-- <use xlink:href="{{ asset('icons/coreui.svg#cil-bug') }}"></use> --}}
    {{-- </svg> --}}
    {{-- Child menu --}}
    {{-- </a> --}}
    {{-- </li> --}}
    {{-- </ul> --}}
    {{-- </li> --}}
</ul>
