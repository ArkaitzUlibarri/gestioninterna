@extends('layouts.app')

@section('content')
    <div class="container">   
        <h2>PROJECTS</h2>
        <!--<p>Lista con los proyectos de la empresa:</p>-->   

            @include('projects.filter')   

            <div class="clearfix" ></div>
             
             <table class="table table-hover table-condensed" style="margin: 2em 0 5em 0;">
                <thead>
                    <th>#</th>
                    <th>Project</th>
                    <th>Customer</th>
                    <th>Start date</th>
                    <th>End date</th>
                    <th>Actions</th>
                </thead>

                @foreach($projects as $project)
                    <tbody>
                        <tr>
                            <td>{{$loop->iteration}}</td>
                            <td>
                                <a a title="Show" href="{{ url('projects' . '/' . $project->id . '/') }}">
                                    {{$project->name}}
                                </a>
                            </td>
                            <td>{{ strtoupper($project->customer)}}</td>
                            <td>{{$project->start_date}}</td>
                            <td>{{empty($project->end_date) ? "In progress" : $project->end_date }}</td>
                            <td>
                                @if(empty($project->end_date))
                                    <a class="btn btn-primary btn-sm"
                                       type="button" href="{{ url('projects' . '/' . $project->id . '/' . 'edit') }}">
                                       <span class="glyphicon glyphicon-edit"></span> Edit
                                    </a>
                                    <a class="btn btn-primary btn-sm"
                                       type="button" href="{{ url('projects' . '/' . $project->id . '/addgroup/' . '/') }}">
                                       Add Groups
                                    </a>
                                @else
                                    <a class="btn btn-warning btn-sm"
                                       type="button" href="{{ url('projects' . '/' . $project->id . '/' . 'edit') }}">
                                       <span class="glyphicon glyphicon-folder-open"></span> Reopen
                                    </a>
                                @endif
                            </td>
                        </tr>
                    </tbody>
                @endforeach
            </table>
            

            <div align="right" class="form-group">  
                <a type="button" class="btn btn-default" href = "{{ url('groups') }}">
                    Group List
                </a>
               <a type="button" class="btn btn-default" href="{{ url('/projects/create') }}">
                    Add Project
               </a>
            </div>
    </div>
@endsection