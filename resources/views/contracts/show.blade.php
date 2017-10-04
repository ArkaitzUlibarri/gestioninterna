@extends('layouts.app')

@section('content')
	
	<div class="row">
	    <div class="col-xs-12 col-sm-offset-1 col-sm-10">
	        @include('contracts.showPartials.card')
	    </div>
	</div>

    @if(count($contract->teleworking)>0)
    <div class="row">
        <div class="col-xs-12 col-sm-offset-1 col-sm-10">
            @include('contracts.showPartials.teleworking')
        </div>
    </div>
	@endif

	@if(count($contract->reductions)>0)
    <div class="row">
        <div class="col-xs-12 col-sm-offset-1 col-sm-10">
            @include('contracts.showPartials.reductions')
        </div>
    </div>
	@endif

	<div class="row">
	    <div class="col-xs-12 col-sm-offset-1 col-sm-10">  
	    	<div class="pull-right">   
	        	<a class="btn btn-default custom-btn-width" href="{{ url('contracts') }}">Back</a>   
	        </div>   
	    </div>
	</div>	

@endsection