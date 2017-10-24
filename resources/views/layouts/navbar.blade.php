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
                    @if(Auth::user()->primaryRole() == 'admin')
                         <li><a href="{{ url('users') }}">Users</a></li> 
                         <li><a href="{{ url('contracts') }}">Contracts</a></li>
                         <li><a href="{{ url('projects') }}">Projects</a></li>  
                    @elseif(Auth::user()->primaryRole() == 'manager')                   
                         <li><a href="{{ url('users') }}">Users</a></li>   
                         <li><a href="{{ url('projects') }}">Projects</a></li>            
                    @endif
                    
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
                                <a href  = "{{ url('users' . '/' . Auth::user()->id . '/') }}">
                                    <span class="glyphicon glyphicon-user"></span> Profile
                                </a>
                                <a href="{{ url('holidays') }}">
                                    <span class="glyphicon glyphicon-tent"></span> Holidays
                                </a>
                                <a href="{{ url('evaluations') }}">
                                    <span class="glyphicon glyphicon-education"></span> Evaluations      
                                </a>
                                <a href="{{ url('validation') }}">
                                    <span class="glyphicon glyphicon-scale"></span> Validation      
                                </a>
                                <a href="{{ url('workingreports') }}">
                                    <span class="glyphicon glyphicon-time"></span> Working Reports    
                                </a>
                                <a href="{{ route('logout') }}"
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