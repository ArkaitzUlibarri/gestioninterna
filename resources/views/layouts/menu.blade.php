@if(Auth::user()->primaryRole() == 'manager' || Auth::user()->primaryRole() == 'admin')   
<a href= "{{ url('users') }}"
    onmouseover="this.className='list-group-item active';"  
    onmouseout="this.className='';" >
    <span class="glyphicon glyphicon-user"></span> Users
</a>
@endif

@if(Auth::user()->primaryRole() == 'admin')   
<a href= "{{ url('contracts') }}"  
    onmouseover="this.className='list-group-item active';"  
    onmouseout="this.className='';" >
    <span class="glyphicon glyphicon-briefcase"></span> Contracts
</a>
@endif

@if(Auth::user()->primaryRole() == 'manager' || Auth::user()->primaryRole() == 'admin')   
<a href= "{{ url('projects') }}" 
    onmouseover="this.className='list-group-item active';"  
    onmouseout="this.className='';" >
    <span class="glyphicon glyphicon-education"></span> Projects
</a>
@endif

<a href="{{ url('holidays') }}" 
    onmouseover="this.className='list-group-item active';"  
    onmouseout="this.className='';" >
    <span class="glyphicon glyphicon-calendar"></span> Holidays
</a>

<a href="{{ url('workingreports') }}" 
    onmouseover="this.className='list-group-item active';"  
    onmouseout="this.className='';" >
    <span class="glyphicon glyphicon-time"></span> Reports    
</a>

<a href="{{ url('validation') }}" 
    onmouseover="this.className='list-group-item active';"  
    onmouseout="this.className='';" >
    <span class="glyphicon glyphicon-check"></span> Validation      
</a>

<a href="{{ url('evaluations') }}" 
    onmouseover="this.className='list-group-item active';"  
    onmouseout="this.className='';" >
    <span class="glyphicon glyphicon-stats"></span> Performance      
</a>