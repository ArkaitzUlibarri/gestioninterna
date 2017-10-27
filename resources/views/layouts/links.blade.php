@if(Auth::user()->primaryRole() == 'manager' || Auth::user()->primaryRole() == 'admin')                   
     <li><a href="{{ url('users') }}"> Users</a></li>           
@endif

@if(Auth::user()->primaryRole() == 'admin')
    <li><a href="{{ url('contracts') }}"> Contracts</a></li>
@endif

@if(Auth::user()->primaryRole() == 'manager' || Auth::user()->primaryRole() == 'admin')                    
     <li><a href="{{ url('projects') }}"> Projects</a></li>            
@endif

 <li><a href="{{ url('holidays') }}"> Holidays</a></li>
 <li><a href="{{ url('workingreports') }}"> Reports</a></li>
 <li><a href="{{ url('validation') }}">Validation</a></li>
 <li><a href="{{ url('evaluations') }}" >Performance</a></li>