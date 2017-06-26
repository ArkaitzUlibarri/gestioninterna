@extends('layouts.app')

@section('content')

<div class="panel panel-primary">
    <div class="panel-heading">
        @include('contracts.filter')
        <h3 class="panel-title" style="margin-top: 7px;">CONTRACTS</h3>
        <div class="clearfix"></div>
    </div>

    <div class="panel-body">
        <table class="table table-hover table-condensed">
            <thead>
                <th>Employee</th>
                <th>Contract</th>
                <th>Start date</th>
                <th>Estimated end date</th>
                <th>End date</th>   
                <th>Actions</th>
            </thead>
            <tbody>
                @foreach($contracts as $contract)
                    <tr>
                        <td><a title="Show" href="{{ url('contracts/' . $contract->id) }}">{{ $contract->full_name }}</a></td>
                        <td>{{ $contract->contract_types}}</td>
                        <td>{{ $contract->start_date}}</td>
                        <td>{{ empty($contract->estimated_end_date) ? "None" : "$contract->estimated_end_date"}}</td>    
                        <td>{{ empty($contract->end_date) ? "In progress" : "$contract->end_date"}}</td>          
                        <td>
                            @if (empty($contract->end_date))
                                <a title="Edit" class="btn btn-default btn-sm" type="button" 
                                   href="{{ url('contracts/' . $contract->id . '/edit') }}">
                                   <span class="glyphicon glyphicon-edit"></span> Edit
                                </a>
                            @else
                                <a class="btn btn-warning btn-sm"
                                   type="button" href="{{ url('contracts/' . $contract->id . '/edit') }}">
                                   <span class="glyphicon glyphicon-folder-open"></span> Reopen
                                </a>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{ $contracts->links() }}

        <hr>

        @include('layouts.flash')
        @include('layouts.errors')

        <div align="right" class="form-group">  
            <a type="button" class="btn btn-success btn-sm" href="{{ url('/contracts/create') }}">
                <span class="glyphicon glyphicon-plus"></span> Add Contract
            </a>
        </div>
    </div>
        
</div>
@endsection