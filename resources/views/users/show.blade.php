@extends('layouts.app')

@section('content')
    <div class="container">

        <dl class="dl-horizontal">
            <dt>Employee ID</dt>
            <dd>{{ $user->id }}</dd>
        </dl>

        <dl class="dl-horizontal">
            <dt>Name</dt>
            <dd>{{ $user->name }}</dd>
        </dl>

        <dl class="dl-horizontal">
            <dt>Lastname 1</dt>
            <dd>{{ $user->lastname_1 }}</dd>
        </dl>

        <dl class="dl-horizontal">
            <dt>Lastname 2</dt>
            <dd>{{ $user->lastname_2 }}</dd>
        </dl>

        <dl class="dl-horizontal">
            <dt>Email</dt>
            <dd>{{ $user->email}}</dd>
        </dl>

        <dl class="dl-horizontal">
            <dt>Role</dt>
            <dd>
                @if(! Auth::user()->isAdmin() && Auth::user()->isPM())
                    PROJECT MANAGER
                @else
                    {{ strtoupper($user->role)}}
                @endif  
            </dd>
        </dl>

        <div class="row">
            @include('contracts.showUsers')
        </div>
        
        <div class="row">
            @include('categories.show')
        </div>

        <div class="row">
            @include('groups.showUsers')
        </div>

        @if(Auth::user()->isAdmin())
            <a title="Back" class="btn btn-default" href="{{ url('users') }}">
                Back
            </a>
        @endif

    </div>
@endsection