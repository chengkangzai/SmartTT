<header class="main-header">
    <a href="{{route('home')}}" class="logo">
        <!-- mini logo for sidebar mini 50x50 pixels -->
        <span class="logo-mini">{{config('app.alias')}}</span>
        <!-- logo for regular state and mobile devices -->
        <span class="logo-lg">{{config('app.name')}}</span>
    </a>

    <nav class="navbar navbar-static-top " role="navigation">
        <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
            <span class="sr-only">Toggle navigation</span>
        </a>
        <div class="navbar-custom-menu ">
            <ul class="nav navbar-nav">
                <li class="dropdown user user-menu">
                    <!-- Menu Toggle Button -->
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <!-- The user image in the navbar-->
                        <img src="/dist/img/user2-160x160.jpg" class="user-image" alt="User Image">
                        <!-- hidden-xs hides the username on small devices so only the image appears. -->
                        <span class="hidden-xs">{{Auth::user()->name}}</span>
                    </a>
                    <ul class="dropdown-menu">
                        <!-- The user image in the menu -->
                        <li class="user-header">
                            <a href="{{route('user.edit',['user'=>Auth::user()->id])}}">
                                <img src="/dist/img/user2-160x160.jpg" class="img-circle" alt="User Image">
                            </a>
                            <p>{{Auth::user()->name}}</p>
                        </li>
                        <li class="user-footer">
                            <div class="pull-left">
                                <a href="{{route('user.edit',['user'=>Auth::user()->id])}}"
                                   class="btn btn-default btn-flat">Profile</a>
                            </div>
                            <div class="pull-right">
                                <form action="{{route('logout')}}" method="POST">
                                    @csrf
                                    <button type="submit" role="button" class="btn btn-default btn-flat">Sign out
                                    </button>
                                </form>
                            </div>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
</header>
