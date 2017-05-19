@extends('layouts.app')

@section('content')
    <div class="container">   
        <h2>USERS</h2>
        <!--<p>Lista con los empleados de la compañia:</p>-->
            @include('users.filter')

            <div class="table-responsive">     
                <table class="table table-hover">
                    <thead>
                        <th>Employee ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Actions</th>
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
                                    <a href = "{{ url('users' . '/' . $user->id . '/' . 'edit') }}" 
                                    title="Edit" class="btn btn-primary btn-sm" aria-hidden="true"><span class="glyphicon glyphicon-edit"></span> Edit</a>
                                </td>
                            </tr>
                        </tbody>
                    @endforeach
                </table>  
            </div>

            <div align="center" class="form-group">  
                {{ $users->links() }}
            </div>

            <div align="right" class="form-group">  
                <a type="button" title="Add User" class=" btn btn-default" href="{{ url('/users/create') }}">
                    Add User
                </a>
            </div>
    </div>
@endsection
