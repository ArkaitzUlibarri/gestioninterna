@extends('layouts.app')

@section('content')

<div class="panel panel-primary">
    <div class="panel-heading">
        @include('projects.indexPartials.filter')
        <h3 class="panel-title" style="margin-top: 7px;">PROJECTS</h3>
        <div class="clearfix"></div>
    </div>

    <div class="panel-body">
        <table class="table table-hover table-condensed">
            <thead>
                <th>#</th>
                <th>Project</th>
                <th>Customer</th>
                @if(Auth::user()->primaryRole() == 'admin')
                    <th>PM</th>
                @endif
                <th>Start date</th>
                <th>End date</th>
                <th class="custom-table-action-th">Actions</th>
            </thead>          
            <tbody>
                @foreach($projects as $project)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td><a a title="Show" href="{{ url('projects' . '/' . $project->id . '/') }}">{{ strtoupper($project->name) }}</a></td>
                        <td>{{ strtoupper($project->customer) }}</td>
                        @if(Auth::user()->primaryRole() == 'admin')
                            <td>{{ ucwords($project->pm) }}</td>
                        @endif
                        <td>{{ $project->start_date }}</td>
                        <td>{{ empty($project->end_date) ? "In progress" : $project->end_date }}</td>
                        <td>
                            @if(empty($project->end_date))
                                <a class="btn btn-default btn-sm custom-btn-width"
                                   type="button" 
                                   href="{{ url('projects' . '/' . $project->id . '/' . 'edit') }}">
                                   <span class="glyphicon glyphicon-edit"></span> Edit
                                </a>
                                <a class="btn btn-default btn-sm custom-btn-width"
                                   type="button" 
                                   href="{{ url('projects' . '/' . $project->id . '/addgroup/' . '/') }}">
                                   Add Groups
                                </a>
                            @else
                                <a class="btn btn-warning btn-sm custom-btn-width"
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

        <hr>
        
        @include('layouts.flash')
        @include('layouts.errors')

        <div class="btn-toolbar"> 
            
            <div class="btn-group">
                {{ $projects->links() }}
            </div>

            <div class="btn-group pull-right">
                <a type="button" class="btn btn-default btn-sm" href="{{ url('groups') }}">
                    <span class="glyphicon glyphicon-list-alt"></span> Group List
                </a>

                <a type="button" class="btn btn-success btn-sm" href="{{ url('/projects/create') }}">
                    New Project
                </a>
            </div>

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