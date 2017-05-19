@extends('layouts.app')

@section('content')
<div class="container">   
    <h2>Working Reports</h2>
    <!--<p>Lista con los reportes de los empleados:</p>-->

        @include('workingreports.filter')

        <div class="table-responsive">
            <table class="table table-hover table-condensed">
                <thead>
                    <th>Employee</th>
                    <th>Date</th>
                    <th>Stated Hours</th>
                    <th>Validated (RP)</th>
                    <th>Validated (ADMIN)</th>
                    <th>Actions</th>
                </thead>

                @foreach($workingreports as $workingreport)
                    <tbody>
                        <tr>
                            <td>{{$workingreport->fullname}}</td>
                            <td><a href = "{{ url('/workingreports/add', [$workingreport->user_id, $workingreport->created_at]) }}"
                                   title="Edit" aria-hidden="true">
                                     {{$workingreport->created_at}}
                                </a>
                            </td>
                            <td>{{$workingreport->horas_reportadas}}</td>   
                            <td>
                                <span class="{{$workingreport->horas_validadas_pm != 0 ? 'glyphicon glyphicon-remove':'glyphicon glyphicon-ok'}}" aria-hidden="true"></span>
                            </td> 
                            <td>
                                <span class="{{$workingreport->horas_validadas_admin != 0 ? 'glyphicon glyphicon-remove':'glyphicon glyphicon-ok'}}" aria-hidden="true"></span>
                            </td> 
                            <td>
                                <button title="Invalidate" class="btn btn-danger">
                                    <span class="glyphicon glyphicon-remove"></span>
                                </button>
                                <button title="Validate" class="btn btn-success">
                                    <span class="glyphicon glyphicon-ok"></span>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                @endforeach
            </table>
        </div>

        <div align="right" class="form-group">  
           <a type="button" title="New Report" class="btn btn-default" href="{{ url('/workingreports/add', [$user_id,$today->format('Y-m-d')] ) }}">
                New Report
            </a>
        </div>
        
</div>
@endsection

@push('script-bottom')
    <script type = "text/javascript">

    </script>
    
    <script src="{{ asset('js/validate.js') }}"></script>
@endpush