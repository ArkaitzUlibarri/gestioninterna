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
                            <tr>
                                @if($group->enabled)
                                    <td>{{$loop->iteration}}</td>
                                    <td>{{ strtoupper($group->project) }}</td>
                                    <td>{{ ucfirst($group->name) }}</td>  
                                @else
                                    <td><s>{{$loop->iteration}}</s></td>
                                    <td><s>{{ strtoupper($group->project) }}</s></td>
                                    <td><s>{{ ucfirst($group->name) }}</s></td>  
                                @endif        
                            </tr>
                         @endforeach
                    </tbody>
                </table>

                <hr>

                <div align="right" class="form-group">      
                    <a class="btn btn-default" href="{{ url('projects') }}">Back</a>
                </div>
            </div>
        </div>

    </div>
</div>

@endsection