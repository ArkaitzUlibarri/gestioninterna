<div class="panel panel-primary">

    <div class="panel-heading">Profile</div>

    <div class="panel-body">
        <p class="col-xs-12 col-sm-6"><strong>Employee ID: </strong> {{ $user->id }}</p>
        <p class="col-xs-12 col-sm-6"><strong>Role: </strong> 
            @if (! Auth::user()->isAdmin() && Auth::user()->isPM())
                PROJECT MANAGER
            @else
                {{ strtoupper($user->role)}}
            @endif 
        </p>
        <p class="col-xs-12 col-sm-6"><strong>Username: </strong> {{ $user->username }}</p>
        <p class="col-xs-12 col-sm-6"><strong>Email: </strong> {{ $user->email }}</p>
        <p class="col-xs-12 col-sm-6"><strong>Name: </strong> {{ $user->name }}</p>
        <p class="col-xs-12 col-sm-6"><strong>Lastname: </strong> {{ $user->lastname }}</p>
        
    </div>
</div>