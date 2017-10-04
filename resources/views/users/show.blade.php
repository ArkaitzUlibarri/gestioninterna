@extends('layouts.app')

@section('content')

<div class="row">
    <div class="col-xs-12 col-sm-offset-1 col-sm-10">
        @include('users.generalPartials.card')
    </div>
</div>

@if (count($contracts) > 0)
    <div class="row">
        <div class="col-xs-12 col-sm-offset-1 col-sm-10">
            @include('users.showPartials.contracts')
        </div>
    </div>
@endif

@if (count($categories) > 0)
    <div class="row">
        <div class="col-xs-12 col-sm-offset-1 col-sm-10">
            @include('users.showPartials.categories')
        </div>
    </div>
@endif

@if (count($groups) > 0)
    <div class="row">
        <div class="col-xs-12 col-sm-offset-1 col-sm-10">
            @include('users.showPartials.groups')
        </div>
    </div>
@endif

@if (count($holidays) > 0)
    <div class="row">
        <div class="col-xs-12 col-sm-offset-1 col-sm-10">
            @include('users.showPartials.holidays')
        </div>
    </div>
@endif

@if(Auth::user()->primaryRole() == 'admin')
    <div class="row">
        <div class="col-xs-12 col-sm-offset-1 col-sm-10">
            <div class="pull-right">
                <a class="btn btn-default custom-btn-width" href="{{ url('users') }}">Back</a>
            </div>
        </div>
    </div>
 @endif

@endsection