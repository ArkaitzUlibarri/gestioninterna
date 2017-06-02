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
            <dd>{{ strtoupper($user->role)}}</dd>
        </dl>

        @include('categories.show')
        @include('groups.showUsers')

        <a title="Back" class="btn btn-primary" href="{{ url('users') }}">
            Back
        </a>

    </div>
@endsection