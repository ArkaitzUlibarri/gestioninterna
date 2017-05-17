@extends('layouts.app')

@section('content')
    <div class="container">   
        <h2>PROJECTS</h2>
        <!--<p>Lista con los proyectos de la empresa:</p>-->   

            @include('projects.filter')   
             
             <table class="table table-hover">
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
                                <a class="btn btn-primary btn-sm {{ empty($project->end_date) ? '' : 'disabled' }}"
                                   type="button" href="{{ url('projects' . '/' . $project->id . '/' . 'edit') }}">
                                   <span class="glyphicon glyphicon-edit"></span> Edit
                                </a>
                                <a class="btn btn-primary btn-sm {{ empty($project->end_date) ? '' : 'disabled' }}"
                                   type="button" href="{{ url('projects' . '/' . $project->id . '/addgroup/' . '/') }}">
                                   <span class="glyphicon glyphicon-plus"></span> Add Group
                                </a>
                            </td>
                        </tr>
                    </tbody>
                @endforeach
            </table>
            

            <div align="right" class="form-group">  
               <a type="button" class="btn btn-success" href="{{ url('/projects/create') }}"><span class="glyphicon glyphicon-plus"></span> Add Project</a>
            </div>
    </div>
@endsection