@extends('layouts.app')

@section('content')
<div class="panel panel-primary">

    <div class="panel-heading">
        @include('users.filter')
        <h3 class="panel-title" style="margin-top: 7px;">Users</h3>
        <div class="clearfix"></div>
    </div>

    <div class="panel-body">
        <div class="table-responsive" >     
            <table class="table table-hover table-condensed">
                <thead>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th class="custom-table-action-th">Actions</th>
                </thead>
                <tbody>
                    @foreach($users as $user)
                    <tr>
                        <td>{{ $user->id }}</td>
                        <td><a href="{{ url('users'.'/'.$user->id.'/') }}" title="Show"> {{ $user->fullname }}</a></td>
                        <td>{{ $user->email }}</td>
                        <td>
                            @if (Auth::user()->isAdmin())
                                <a title="Edit"
                                   href = "{{ url('users' . '/' . $user->id . '/' . 'edit') }}" 
                                   class="btn btn-default btn-sm"
                                   aria-hidden="true">
                                   <span class="glyphicon glyphicon-edit"></span> Edit
                                </a>
                            @endif

                            @if (Auth::user()->isAdmin() || Auth::user()->isPM())
                            <a title="Groups"
                               href="{{ url('users/' . $user->id . '/groups') }}"
                               class="btn btn-default btn-sm"
                               aria-hidden="true">Proyects &amp; Groups</a>
                            @endif
                        </td>
                    </tr>
                    @endforeach

                </tbody>
            </table>  
        </div>

        {{ $users->links() }}

<!--
        <hr>

        <div align="right" class="form-group">  
            <a type="button" title="Add User" class=" btn btn-success btn-sm" href="{{ url('/users/create') }}" role="button">
                <span class="glyphicon glyphicon-plus"></span> New User
            </a>
        </div>

-->
    </div>
</div>
@endsection
