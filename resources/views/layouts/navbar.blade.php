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
                    <a class="navbar-brand" href="{{ url('http://3dbconsult.com','/home') }}">
                        {{ config('app.name','3db Intranet') }}
                    </a>

                </div>

                <div class="collapse navbar-collapse" id="app-navbar-collapse">
                    <!-- Left Side Of Navbar 
                    <ul class="nav navbar-nav">
                        &nbsp;
                    </ul> -->

                    <!-- Right Side Of Navbar -->
                    <ul class="nav navbar-nav navbar-right">
                        <!-- Authentication Links -->
                        @if (Auth::guest())
                            <li><a href="{{ route('login') }}">Login</a></li>
                            <!--<li><a href="{{ route('register') }}">Register</a></li>-->
                        @else      
                            <li><a href  = "{{ url('users' . '/' . Auth::user()->id . '/') }}">Profile</a></li>       
                            @if(Auth::user()->isAdmin())
                                 <li><a href  = "{{ url('users') }}">Users</a></li> 
                                 <li><a href  = "{{ url('contracts') }}">Contracts</a></li>
                                 <li><a href = "{{ url('projects') }}">Projects</a></li>  
                            @elseif(Auth::user()->isPM()) 
                                 <li><a href  = "{{ url('users') }}">Users</a></li>   
                                 <li><a href = "{{ url('projects') }}">Projects</a></li>            
                            @endif
                             <li><a href = "{{ url('workingreports') }}">Working Reports</a></li> 
                            
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                    @if(Auth::user()->isAdmin())
                                        {{ Auth::user()->name }} {{ Auth::user()->lastname_1 }} (Admin)
                                    @elseif(Auth::user()->isPM())
                                        {{ Auth::user()->name }} {{ Auth::user()->lastname_1 }} (PM)
                                    @elseif(Auth::user()->isRole('tools'))
                                        {{ Auth::user()->name }} {{ Auth::user()->lastname_1 }} (Tools)
                                    @else
                                        {{ Auth::user()->name }} {{ Auth::user()->lastname_1 }} (User)
                                    @endif
                                    <span class="caret"></span>
                                </a>

                                <ul class="dropdown-menu" role="menu">
                                    <li>
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