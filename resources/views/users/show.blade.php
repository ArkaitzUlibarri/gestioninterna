@extends('layouts.app')

@section('content')

<div class="row">
    <div class="col-xs-12 col-sm-offset-1 col-sm-10">
        @include('users.card')
    </div>
</div>

@if (count($contracts) > 0)
    <div class="row">
        <div class="col-xs-12 col-sm-offset-1 col-sm-10">
            @include('contracts.showUsers')
        </div>
    </div>
@endif

@if (count($categories) > 0)
    <div class="row">
        <div class="col-xs-12 col-sm-offset-1 col-sm-10">
            @include('categories.show')
        </div>
    </div>
@endif

@if (count($groups) > 0)
    <div class="row">
        <div class="col-xs-12 col-sm-offset-1 col-sm-10">
            @include('groups.showUsers')
        </div>
    </div>
@endif

@if(Auth::user()->isAdmin())
    <div class="row">
        <div class="col-xs-12 col-sm-offset-1 col-sm-10">
            <div class="pull-right">
                <a class="btn btn-default custom-btn-width" href="{{ url('users') }}">Back</a>
            </div>
        </div>
    </div>
 @endif

@endsection