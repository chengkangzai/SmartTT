<ul class="sidebar-nav" data-coreui="navigation" data-simplebar>

    <li class="d-block d-md-none nav-item">
        <a class="nav-link" href="{{ route('front.index') }}">
            <img src="{{ asset('button_smart-tt.png') }}" alt="" height="40">
        </a>
        <div style="border: 1"></div>
    </li>

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
