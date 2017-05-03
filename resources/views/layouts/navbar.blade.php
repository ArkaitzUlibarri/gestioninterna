        <nav class="navbar navbar-default navbar-static-top">
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
                            <li><a href="{{ route('register') }}">Register</a></li>
                        @else
                        
                             <li><a href  = "{{ url('users') }}">Users</a></li> 
                             <li><a href  = "{{ url('contracts') }}">Contracts</a></li>
                             <li><a href = "{{ url('projects') }}">Projects</a></li>  
                             <li><a href = "{{ url('groups') }}">Groups</a></li>  
                             <li><a href = "{{ url('workingreports') }}">Working Reports</a></li> 
                            
                        <!--    
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                    Menu <span class="caret"></span>                           
                                </a>

                                <ul class="dropdown-menu" role="menu">                                
                                     <li><a href  = "{{ url('contracts') }}">Contracts</a></li>
                                     <li><a href = "{{ url('groups') }}">Groups</a></li>  
                                     <li><a href = "{{ url('projects') }}">Projects</a></li>  
                                     <li><a href  = "{{ url('users') }}">Users</a></li>                                    
                                     <li><a href = "{{ url('workingReports') }}">Working Reports</a></li>  
                                </ul>
                            </li>
                        -->  
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                    {{ Auth::user()->name }} {{ Auth::user()->lastname_1 }} <span class="caret"></span>
                                </a>

                                <ul class="dropdown-menu" role="menu">
                                    <li>
                                        <a href="{{ route('logout') }}"
                                            onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                            Logout
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