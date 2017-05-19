@extends('layouts.app')

@section('content')
    <div class="container">   
        <h2>CONTRACTS</h2>
        <!--<p>Lista con los contratos de los empleados:</p>-->

            @include('contracts.filter')
            <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <th>Employee</th>
                    <th>Contract</th>
                    <th>Start date</th>
                    <th>Estimated end date</th>
                    <th>End date</th>   
                    <th>Actions</th>
                </thead>

                @foreach($contracts as $contract)
                    <tbody>
                        <tr>
                            <td>    
                                <a title="Show" href="{{ url('contracts/' . $contract->id) }}">
                                    {{$contract->name}} {{$contract->lastname_1}} {{$contract->lastname_2}}
                                </a>
                            </td>
                            <td>{{$contract->contract_types}}</td>
                            <td>{{$contract->start_date}}</td>
                            <td>{{empty($contract->estimated_end_date) ? "None" : "$contract->estimated_end_date"}}</td>    
                            <td>{{empty($contract->end_date) ? "In progress" : "$contract->end_date"}}</td>          
                            <td>
                                <a title="Edit" class="btn btn-primary btn-sm {{ ! empty($contract->end_date) ? 'disabled' : '' }}" type="button" 
                                   href="{{ url('contracts/' . $contract->id . '/edit') }}">
                                   <span class="glyphicon glyphicon-edit"></span> Edit
                                </a>
                            </td>
                        </tr>
                    </tbody>
                @endforeach
            </table>
            </div>

            <div align="center" class="form-group">  
                {{ $contracts->links() }}
            </div>

            <div align="right" class="form-group">  
                <a type="button" title="Add Contract" class="btn btn-default" href="{{ url('/contracts/create') }}">
                    Add Contract
                </a>
            </div>
            
    </div>
@endsection