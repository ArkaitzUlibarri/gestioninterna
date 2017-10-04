@extends('layouts.app')

@section('content')
<div class="panel panel-primary">

    <div class="panel-heading">
        @include('users.indexPartials.filter')
        <h3 class="panel-title" style="margin-top: 7px;">USERS</h3>
        <div class="clearfix"></div>
    </div>

    <div class="panel-body">
            
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
                    <td><a href="{{ url('users'.'/'.$user->id.'/') }}" title="Show"> {{ ucwords($user->fullname) }}</a></td>
                    <td>{{ $user->email }}</td>
                    <td>
                        @if (Auth::user()->primaryRole() == 'admin')
                            <a title="Edit"
                               href = "{{ url('users' . '/' . $user->id . '/' . 'edit') }}" 
                               class="btn btn-default btn-sm"
                               aria-hidden="true">
                               <span class="glyphicon glyphicon-edit"></span> Edit
                            </a>
                        @endif

                        @if (Auth::user()->primaryRole() == 'admin' || Auth::user()->primaryRole() == 'manager')
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
        
        <hr>

        <div class="btn-toolbar"> 
            
            <div class="btn-group pull-left">
                {{ $users->links() }}
            </div>

        </div>

<!--    
        <div align="right" class="form-group">  
            <a type="button" title="Add User" class=" btn btn-success btn-sm" href="{{ url('/users/create') }}" role="button">
                <span class="glyphicon glyphicon-plus"></span> New User
            </a>
        </div>
-->

    </div>
</div>
@endsection

@push('script-bottom')

    <style>
        .pagination{
            display: inline;
            margin-left: 0.5em;
            margin-right: 0.5em;
        }
    </style>

@endpush
