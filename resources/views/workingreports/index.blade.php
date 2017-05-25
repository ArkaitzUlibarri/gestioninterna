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
                    
                    @if(Auth::user()->isRole('admin') || Auth::user()->isPM())
                        <th>Actions</th>
                    @endif
                    
                </thead>
       
                <tbody>
                    <tr v-for="(item, index) in reports">
                        <td>@{{ item.fullname }}</td>
                        <td>@{{ getWeek(1,item.created_at) }} | @{{ getDayWeek(item.created_at) }}</td>
                        <td>   
                            <a id="ref" v-bind:href="'/workingreports/add/'+item.user_id +'/'+ item.created_at +'/'"  title="Edit" aria-hidden="true">
                                @{{ item.created_at }}
                            </a>               
                        </td>
                        <td>@{{ item.horas_reportadas }}</td>  
                        <td>
                            <div v-if="item.horas_validadas_pm !=0">
                                <span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
                            </div>
                            <div v-else>
                                <span class="glyphicon glyphicon-ok" aria-hidden="true"></span>
                            </div>                                  
                        </td>
                        <td>
                            <div v-if="item.horas_validadas_admin !=0">
                                <span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
                            </div>
                            <div v-else>
                                <span class="glyphicon glyphicon-ok" aria-hidden="true"></span>
                            </div>  
                        </td>

                        @if(Auth::user()->isRole('admin') || Auth::user()->isPM())
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

        <div align="right" class="form-group">  
           <a type="button" title="New Report" class="btn btn-default" v-bind:href="'/workingreports/add/'+user_id +'/'+ getDate() +'/'" >
                New Report
            </a>
        </div>
              
        <pre>@{{$data}}</pre>

        
</div>
@endsection

@push('script-bottom')
    <script type = "text/javascript">
        var workingreport = <?php echo json_encode($workingreports);?>;
        var auth_user     = <?php echo json_encode($auth_user);?>;
        var pm            = '{{ $auth_user->isPM() }}';
    </script>

    <script src="{{ asset('js/validate.js') }}"></script>
@endpush