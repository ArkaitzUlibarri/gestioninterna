@extends('layouts.app')

@section('content')

<div class="panel panel-primary">

    <div class="panel-heading">
        @include('contracts.indexPartials.filter')
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
                        <td><a title="Show" href="{{ url('contracts/' . $contract->id) }}">{{ ucwords($contract->full_name) }}</a></td>
                        <td>{{ ucfirst($contract->contract_types) }}</td>
                        <td>{{ $contract->start_date }}</td>
                        <td>{{ empty($contract->estimated_end_date) ? "None" : "$contract->estimated_end_date" }}</td>    
                        <td>{{ empty($contract->end_date) ? "In progress" : "$contract->end_date" }}</td>          
                        <td>
                            @if (empty($contract->end_date))
                                <a title="Edit" class="btn btn-default btn-sm custom-btn-width" type="button" 
                                   href="{{ url('contracts/' . $contract->id . '/edit') }}">
                                   <span class="glyphicon glyphicon-edit"></span> Edit
                                </a>
                            @else
                                <a title="Reopen" class="btn btn-warning btn-sm custom-btn-width" type="button" 
                                    href="{{ url('contracts/' . $contract->id . '/edit') }}">
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
                {{ $contracts->links() }}
            </div>

            <div class="btn-group pull-right">
                <a type="button" class="btn btn-success btn-sm" href="{{ url('/contracts/create') }}">
                    New Contract
                </a>
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