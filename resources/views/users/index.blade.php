@extends('layouts.app')

@section('content')
    <div class="container">   
        <h2>USERS</h2>
        <!--<p>Lista con los empleados de la compañia:</p>-->
            @include('users.filter')

            <div class="clearfix" ></div>

            <div class="table-responsive" style="margin: 2em 0 5em 0;">     
                <table class="table table-hover table-condensed">
                    <thead>
                        <th>Employee ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        @if(Auth::user()->isAdmin() || Auth::user()->isPM())
                            <th>Actions</th>
                        @endif
                    </thead>

                    @foreach($users as $user)
                        <tbody>
                            <tr>
                                <td>{{$user->id}}</td>
                                <td>
                                    <a  href = "{{ url('users' . '/' . $user->id . '/') }}" title="Show">
                                        {{$user->name}} {{$user->lastname_1}} {{$user->lastname_2}}
                                    </a>
                                </td>
                                <td>{{$user->email}}</td>
                                <td>
                                    @if(Auth::user()->isAdmin())
                                        <a href = "{{ url('users' . '/' . $user->id . '/' . 'edit') }}" 
                                        title="Edit" class="btn btn-primary btn-sm" aria-hidden="true"><span class="glyphicon glyphicon-edit"></span> Edit</a>
                                    @endif

                                    @if(Auth::user()->isAdmin() || Auth::user()->isPM())
                                    <a title="Groups" class="btn btn-primary btn-sm" type="button" 
                                       href="{{ url('users/' . $user->id . '/groups') }}">
                                        Proyects/Groups
                                    </a>
                                    @endif

                                </td>
                            </tr>
                        </tbody>
                    @endforeach
                </table>  
            </div>

            <div align="right" class="form-group">  
                <a type="button" title="Add User" class=" btn btn-default" href="{{ url('/users/create') }}">
                    Add User
                </a>
            </div>
    </div>
@endsection
