<aside class="main-sidebar">
    <section class="sidebar">
        <ul class="sidebar-menu" data-widget="tree">
            <li class="header">Tour & Trip</li>
            <li><a href="{{route('tour.index')}}"><i class="fa fa-link"></i> <span>Tour Management</span></a></li>
            <li><a href="{{route('trip.index')}}"><i class="fa fa-link"></i> <span>Trip Management</span></a></li>
            <li><a href="{{route('airline.index')}}"><i class="fa fa-link"></i> <span>Airline Management</span></a></li>

            <li class="header">Booking</li>
            <li><a href="{{route('booking.index')}}"><i class="fa fa-link"></i> <span>Booking Management</span></a></li>

            <li class="header">User Management</li>
            <li><a href="{{route('user.index')}}"><i class="fa fa-link"></i> <span>User Management</span></a></li>
            @can('View User Role')
            <li><a href="{{route('role.index')}}"><i class="fa fa-link"></i> <span>User Role Management</span></a></li>
            @endcan
        </ul>
    </section>
</aside>
