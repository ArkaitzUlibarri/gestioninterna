<nav class="navbar navbar-inverse navbar-static-top">
    <div class="container">
        <div class="navbar-header">

            <!-- Collapsed Hamburger -->
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                <span class="sr-only">Toggle Navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>

            <!-- Branding Image -->
            <a class="navbar-brand" href="{{ url('/') }}">
                {{ config('app.name','3db Intranet') }}
            </a>

        </div>

        <div class="collapse navbar-collapse" id="app-navbar-collapse">
            <!-- Right Side Of Navbar -->
            <ul class="nav navbar-nav navbar-right">
                <!-- Authentication Links -->
                @if (Auth::guest())
                    <li><a href="{{ route('login') }}">Login</a></li>
                    <!--<li><a href="{{ route('register') }}">Register</a></li>-->
                @else      
                    <!--@include('layouts.links')-->
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">

                            {{ ucwords(Auth::user()->name) }}
                            
                            @if(Auth::user()->primaryRole() == 'admin')
                                (Admin)
                            @elseif(Auth::user()->primaryRole() == 'manager')
                                (PM)
                            @elseif(Auth::user()->role == 'tools')
                                (Tools)
                            @else
                                (User)
                            @endif
                            
                            <span class="caret"></span>
                        </a>

                        <ul class="dropdown-menu" role="menu">
                            <li>
                                <a href= "{{ url('users' . '/' . Auth::user()->id . '/') }}" 
                                    onmouseover="this.className='list-group-item active';"  
                                    onmouseout="this.className='';" >
                                    <span class="glyphicon glyphicon-home"></span> Profile
                                </a>
                                @include('layouts.menu')
                                <a href="{{ route('logout') }}"
                                     onmouseover="this.className='list-group-item active';"  
                                     onmouseout="this.className='';" 
                                     onclick="event.preventDefault();
                                     document.getElementById('logout-form').submit();">
                                    <span class="glyphicon glyphicon-log-out"></span> Logout
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    {{ csrf_field() }}
                                </form>
                            </li>
                        </ul>
                        
                    </li>
                @endif
            </ul>
        </div>
    </div>
</nav>