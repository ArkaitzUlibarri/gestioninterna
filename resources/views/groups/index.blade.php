@extends('layouts.app')

@section('content')
    <div class="container">   
        <h2>GROUPS</h2>
        <!--<p>Lista con los grupos de los proyectos:</p> -->    
             <table class="table table-hover">
                <thead>
                    <th>#</th>
                    <th>Project</th>
                    <th>Group</th> 
                    <!--<th>Actions</th>-->
                </thead>

                @foreach($groups as $group)
                    <tbody>
                        <tr>
                            @if($group->enabled)
                                <td>{{$loop->iteration}}</td>
                                <td>{{$group->project}}</td>
                                <td>{{$group->name}}</td>  
                            @else
                                <td><s>{{$loop->iteration}}</s></td>
                                <td><s>{{$group->project}}</s></td>
                                <td><s>{{$group->name}}</s></td>  
                            @endif        
                                <!--<td><a class="btn btn-default" type="button" href="{{ url('groups/' . $group->id) }}">Show</a>
                                    <a class="btn btn-default" type="button" href="{{ url('groups/' . $group->id . '/edit') }}">Edit</a></td>-->
                        </tr>
                    </tbody>
                @endforeach
            </table>
            <!--<a type="button" class="btn btn-success" href="{{ url('/groups/create') }}">Add</a>-->
    </div>
@endsection