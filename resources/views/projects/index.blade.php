@extends('layouts.app')

@section('content')

<div class="panel panel-primary">
    <div class="panel-heading">
        @include('projects.filter')
        <h3 class="panel-title" style="margin-top: 7px;">PROJECTS</h3>
        <div class="clearfix"></div>
    </div>

    <div class="panel-body">
        <table class="table table-hover table-condensed">
            <thead>
                <th>#</th>
                <th>Project</th>
                <th>Customer</th>
                <th>Start date</th>
                <th>End date</th>
                <th class="custom-table-action-th">Actions</th>
            </thead>          
            <tbody>
                @foreach($projects as $project)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td><a a title="Show" href="{{ url('projects' . '/' . $project->id . '/') }}">{{ $project->name }}</a></td>
                        <td>{{ strtoupper($project->customer) }}</td>
                        <td>{{ $project->start_date }}</td>
                        <td>{{ empty($project->end_date) ? "In progress" : $project->end_date }}</td>
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
                                   type="button"
                                   href="{{ url('projects' . '/' . $project->id . '/' . 'edit') }}">
                                   <span class="glyphicon glyphicon-folder-open"></span> Reopen
                                </a>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{ $projects->links() }}

        <hr>
        
        <div align="right" class="form-group">
            <a type="button" class="btn btn-default btn-sm" href="{{ url('groups') }}">
                <span class="glyphicon glyphicon-list-alt"></span> Group List
            </a>

            <a type="button" class="btn btn-success btn-sm" href="{{ url('/projects/create') }}">
                <span class="glyphicon glyphicon-plus"></span> Add Project
            </a>
        </div>
    </div>

@endsection