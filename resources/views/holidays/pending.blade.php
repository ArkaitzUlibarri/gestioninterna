@extends('layouts.app')

@section('content')	

<div class="panel panel-primary">

    <div class="panel-heading"><STRONG>Holidays pending of validation</STRONG></div>

    <div class="panel-body">

    	<table class="table table-hover table-condensed">

    		<thead>
    			<th>Username</th>
    			<th>Year</th>
                <th>Weeks</th>
    			<th>Count</th>
    		</thead>

    		<tbody>
    			@foreach($users_holidays as $item)
	    			<tr>
	    				<td>
                            <a href="{{ url('holidays_validation/' . $item->user_id . '/') }}">{{ucwords($item->name)}} {{ucwords($item->lastname)}}</a>
                        </td>
	    				<td>{{$item->yeardate}}</td>
                        <td>{{$item->weekdate}}</td>
	    				<td>{{$item->count}}</td>  
	    			</tr>
    			@endforeach
    		</tbody>

    	</table>

    </div>
</div>

@endsection