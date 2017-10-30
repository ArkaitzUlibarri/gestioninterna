@extends('layouts.app')

@section('content')

<div class="row">
    <div class="col-xs-12 col-sm-offset-3 col-sm-6">
        <div class="panel panel-primary">

            <div class="panel-heading">GROUPS</div>

            <div class="panel-body">
                <table class="table table-hover table-condensed">
                    <thead>
                        <th>#</th>
                        <th>Project</th>
                        <th>Group</th> 
                    </thead>
                    <tbody>
                        @foreach($groups as $group)
                            <tr class="{{ $group->enabled ? '' : 'danger' }}">
                                <td class="col-sm-2">{{$loop->iteration}}</td>
                                <td>{{ strtoupper($group->project->name) }}</td>
                                <td>{{ ucfirst($group->name) }}</td>        
                            </tr>
                         @endforeach
                    </tbody>
                </table>

                <hr>

                <div class="btn-toolbar"> 
                    
                    <div class="btn-group">
                        {{ $groups->links() }}
                    </div>

                    <div class="btn-group pull-right">      
                        <a type="button" 
                            class="btn btn-default btn-sm custom-btn-width" 
                            href="{{ url('projects') }}">
                            Back
                        </a>
                    </div>

                </div>

            </div>

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