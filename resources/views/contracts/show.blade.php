@extends('layouts.app')

@section('content')
	
	<div class="row">
	    <div class="col-xs-12 col-sm-offset-1 col-sm-10">
	        @include('contracts.card')
	    </div>
	</div>

    @if(count($contract->teleworking)>0)
    <div class="row">
        <div class="col-xs-12 col-sm-offset-1 col-sm-10">
            @include('contracts.teleworking')
        </div>
    </div>
	@endif

	@if(count($contract->reductions)>0)
    <div class="row">
        <div class="col-xs-12 col-sm-offset-1 col-sm-10">
            @include('contracts.reductions')
        </div>
    </div>
	@endif

	<div class="row">
	    <div class="col-xs-12 col-sm-offset-1 col-sm-10">     
	        <a title="Back" class="btn btn-default" href="{{ url('contracts') }}">Back</a>      
	    </div>
	</div>	

@endsection