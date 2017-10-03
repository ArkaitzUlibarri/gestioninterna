@extends('layouts.app')

@section('content')	

<div class="row" v-if="contract.end_date == null"> 
    <div class="col-xs-12 col-sm-4 col-md-3 col-md-offset-1 col-lg-2 col-lg-offset-2">
        @include('holidays.card')
        @include('holidays.key')
    </div>

    <div class="col-xs-12 col-sm-9 col-md-7 col-lg-6">
        <div id='calendar'></div>
    </div>
</div>

<div class="panel panel-danger" v-else>
      <div class="panel-heading">
            <span class="glyphicon glyphicon-ban-circle" aria-hidden="true"></span> Error
      </div>
      <div class="panel-body">
            User without an active contract
      </div>
</div>

@endsection

@push('script-bottom')
<style>

	.container {
    	width: 100%;
	}

    #calendar {
        min-width: 100%;
       /*max-width: 900px;*/
        margin: 0 auto;
    }

	.table-borderless > tbody > tr > td,
    .table-borderless > tbody > tr > th,
    .table-borderless > tfoot > tr > td,
    .table-borderless > tfoot > tr > th,
    .table-borderless > thead > tr > td,
    .table-borderless > thead > tr > th {
        border: none;
    }

    .cuadrado{
        width: 15px; 
        height: 15px; 
        display: inline-block;
        opacity: 0.3;
        filter: alpha(opacity=30);
    }

    .yellow{
        background-color: yellow;
    }

    .blue{
        background-color: blue;
    }

    .red{
        background-color: red;
    }

    .black{
        background-color: black;
    }

    .validated{
        border-color: black;
        border-style: solid;
        border-width: 3px;
    }

</style>

<link href='//cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.4.0/fullcalendar.min.css' rel='stylesheet' />
<script src='//cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.4.0/fullcalendar.min.js'></script>

<script type="text/javascript">
    var url = "{{ url('/') }}";
    var id = '{!! Auth()->user()->id !!}';
    var user_contract = <?php echo json_encode(Auth()->user()->contracts->first());?>;
</script>

<script src="{{ asset('js/calendar.js') }}"></script>

@endpush