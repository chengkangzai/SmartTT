<aside class="main-sidebar">
    <section class="sidebar">
        <ul class="sidebar-menu" data-widget="tree">
            <li class="header">Tour & Trip</li>
            <li><a href="{{route('tour.index')}}"><i class="fa fa-link"></i> <span>Tour Management</span></a></li>
            <li><a href="{{route('trip.index')}}"><i class="fa fa-link"></i> <span>Trip Management</span></a></li>
            <li><a href="{{route('flight.index')}}"><i class="fa fa-link"></i> <span>Flight Management</span></a></li>

            <li class="header">Booking</li>
            <li><a href="{{route('booking.index')}}"><i class="fa fa-link"></i> <span>Booking Management</span></a></li>

            @canany('View User','Create User','Delete User','View User Role')
            <li class="header">User Management</li>
            @endcanany
            @canany('View User','Create User','Delete User')
            <li><a href="{{route('user.index')}}"><i class="fa fa-link"></i> <span>User Management</span></a></li>
            @endcanany
            @can('View User Role')
            <li><a href="{{route('role.index')}}"><i class="fa fa-link"></i> <span>User Role Management</span></a></li>
            @endcan
        </ul>
    </section>
</aside>
