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
                    <th>Week | Day</th>
                    <th>Date</th>
                    <th>Stated Hours</th>
                    <th>Validated (RP)</th>
                    <th>Validated (ADMIN)</th>
                    
                    @if(Auth::user()->role =='admin')
                        <th>Actions</th>
                    @endif
                    
                </thead>
       
                <tbody>
                    <tr v-for="(item, index) in reports">
                        <td>@{{ item.fullname }} </td>
                        <td>@{{ getWeek(1,item.created_at) }} | @{{ getDayWeek(item.created_at) }}</td>
                        <td>                           
                            @{{ item.created_at }}  
                        </td>
                        <td>@{{ item.horas_reportadas }}</td>  
                        <td>@{{ item.horas_validadas_pm }} </td>
                        <td>@{{ item.horas_validadas_admin }}</td>

                        @if(Auth::user()->role =='admin')
                            <td>
                                <button title="Invalidate" class="btn btn-danger btn-xs" v-on:click="validate(item.user_id, item.created_at)">
                                    <span class="glyphicon glyphicon-remove"></span>
                                </button>
                                <button title="Validate" class="btn btn-success btn-xs" v-on:click.prevent="validate(item.user_id, item.created_at)">
                                    <span class="glyphicon glyphicon-ok"></span>
                                </button>
                            </td>
                        @endif

                    </tr>
                </tbody>
                     
            </table>
        </div>

        <!--
        <div class="table-responsive">
            <table class="table table-hover table-condensed">
                <thead>
                    <th>Employee</th>
                    <th>Week | Day</th>
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
                            <td>{{date('W', strtotime( $workingreport->created_at))}} | {{date('l', strtotime( $workingreport->created_at))}}</td>
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
                                <button title="Invalidate" class="btn btn-danger btn-xs">
                                    <span class="glyphicon glyphicon-remove"></span>
                                </button>
                                <button title="Validate" class="btn btn-success btn-xs">
                                    <span class="glyphicon glyphicon-ok"></span>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                @endforeach
                
            </table>
        </div>

        <div align="right" class="form-group">  
           <a type="button" title="New Report" class="btn btn-default" href="{{ url('/workingreports/add', [$auth_user->id,$today->format('Y-m-d')] ) }}">
                New Report
            </a>
        </div>
        -->
        
        <pre>@{{$data}}</pre>

        
</div>
@endsection

@push('script-bottom')
    <script type = "text/javascript">
        var workingreport = <?php echo json_encode($workingreports);?>;
        var auth_user = <?php echo json_encode($auth_user);?>;
        var categories = <?php echo json_encode($categories);?>;
    </script>

    <script src="{{ asset('js/validate.js') }}"></script>
@endpush