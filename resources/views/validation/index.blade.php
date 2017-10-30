@extends('layouts.app')

@section('content')

    <div class="panel panel-primary">

        <div class="panel-heading">
            @include('validation.partials.filter')
            <div class="clearfix"></div>
        </div>

        <div class="panel-body">

            <table v-show="filtered_reports!=null">

                <thead>
                    <tr>
                        <th>Employee</th>
                        <th v-for="(day, index) in days">
                            <span v-bind:class ="getDayColor(day)" v-bind:title ="getDayTitle(day)">
                                @{{ weekdaysShort[index] }}, @{{ day.substr(5, 5) }}
                            </span>
                        </th>
                    </tr>
                </thead>

                <tbody>
                    <tr v-for="user in users">
                        <td>
                            <strong>@{{ user['name'].toUpperCase() }}</strong>
                            <p>Total Week: @{{ user['total'] }}</p>
                            <!--<p><button class="btn btn-default btn-sm">Validate Week</button></p>-->
                        </td>

                        <td v-for="day in days">

                            <div v-if="hasCard(user['id'], day)"
                                 class="card"
                                 v-bind:class="getCardColor(filtered_reports[user['id']+'|'+day].admin_validation, filtered_reports[user['id']+'|'+day].pm_validation)">

                                <button
                                    style="position:relative; top:-12%; right:-5%;" 
                                    v-bind:title="getValidationButtonTitle(filtered_reports[user['id']+'|'+day].admin_validation,filtered_reports[user['id']+'|'+day].pm_validation,'title')"
                                    class="btn btn-default btn-xs pull-right clickable"
                                    v-on:click="validate(user['id']+'|'+day)">
                                    <span                                    
                                        v-bind:class="getValidationButtonTitle(filtered_reports[user['id']+'|'+day].admin_validation,filtered_reports[user['id']+'|'+day].pm_validation,'other')" aria-hidden="true">
                                    </span>
                                </button> 
                                <a type="button"     
                                    style="position:relative; top:-12%; right:-5%;" 
                                    title="See Report"
                                    class="btn btn-default btn-xs pull-right clickable"
                                    v-bind:href="makeUrl('{{ url('workingreports/add/') }}', [user['id'], day])">
                                    <span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span>
                                </a> 

                                <div v-for="item in filtered_reports[user['id']+'|'+day].items">
                                    <p>- @{{ item.name.toUpperCase() }}, @{{ item.time_slot }}h</p>
                                </div>

                                <div style="margin-top: 10px;padding-top: 10px;border-top: 1px solid #9e9e9e;">
                                    <strong>Total Hours:</strong> @{{ filtered_reports[user['id']+'|'+day].total }}   
                                    <span><div class="pull-right">@{{ filtered_reports[user['id']+'|'+day].manager }}</div></span>                                             
                                </div>

                            </div>
                        </td>
                    </tr>
                </tbody>

            </table>

            <div style="text-align: center; margin-top: 50px; margin-bottom: 50px" v-show="filtered_reports==null">
                No data available...
            </div> 

        </div>

        <div class="panel-footer">
            @include('validation.partials.footer')
            <div class="clearfix"></div>
        </div>

    </div>

    <div class="row front" v-show="upHere">
        @include('validation.partials.key')
    </div>

@endsection

@push('script-bottom')

<style>

    .container {
        width: 100%;
    }

    table {
        border-collapse: collapse;
        width: 100%;
    }

    thead {
        border-bottom: 1px solid #ccc;
    }

    th {
        text-align: center;
        height: 3em;
    }

    td {
        width: 5em;
        height: 7em;
        padding: .2em;
    }

    table .card {
        font-size: 12px;
        width: 100%;
        height: 100%;
        padding: 1em;
        border: 1px solid #dddddd;
    }

    .clickable{
        cursor: pointer;
    }

    .card p {
        margin-bottom: 0px;
    }

    .validated {
        //background: #E1F5FE;
        background: #80d2f7;
    }

    .full-validated {
        background: #c5e1a5;
    }

    .legend{
        font-style: italic;
    }

    .cuadrado{
        width: 15px; 
        height: 15px; 
        display: inline-block;
        margin-right: 1em;
    }

    .bank-holiday{
        color: red;
    }

    /*
    .card p {
        margin-bottom: 2px;
    }
    */
   
</style>

<script type="text/javascript">
    var url = "{{ url('/') }}";
    var id = "{!! Auth()->user()->id !!}";
    var role = "{!! Auth()->user()->primaryRole() !!}";
    var groupsProj = <?php echo json_encode($groupsProjects);?>;    
</script>

<script src="{{ asset('js/week_validation.js') }}"></script>

@endpush