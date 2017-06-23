@extends('layouts.app')

@section('content')

<div class="container">   
    <h2>CONTRACTS</h2>

    @include('contracts.filter')

    <div class="clearfix" ></div>
    
    <div class="table-responsive" style="margin: 2em 0 5em 0;">
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
                            <a title="Edit" class="btn btn-primary btn-sm" type="button" 
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

    </div>

    @include('layouts.flash')
    @include('layouts.errors')

    <div align="right" class="form-group">  
        <a type="button" title="Add Contract" class="btn btn-default" href="{{ url('contracts/create') }}">
            Add Contract
        </a>
    </div>
        
</div>
@endsection