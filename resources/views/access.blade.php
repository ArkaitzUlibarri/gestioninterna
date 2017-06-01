@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-danger">
                <div class="panel-heading"><span class="glyphicon glyphicon-ban-circle" aria-hidden="true"></span> Access Denied</div>

                <div class="panel-body">
                    You are not admin
                </div>
            </div>
        </div>
    </div>
</div>

@endsection