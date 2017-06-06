@if(count($contract->teleworking)>0)
	
	<div class="table-responsive col-sm-5">

		<h4>TELEWORKING</h4>

        <table class="table table-hover">
            <thead>
                <th>Start date</th>
                <th>End date</th>   
                <th>Days</th>
            </thead>

            @foreach($contract->teleworking as $tele)
            <tbody>
                <tr>
                    <td>{{$tele->start_date}}</td>
                    <td>{{$tele->end_date}}</td>
                    <td>
	                    {{$tele->monday ? "Monday |" : "" }}
	                    {{$tele->tuesday ? "Tuesday |" : "" }}
	                    {{$tele->wednesday ? "Wednesday |" : "" }}
	                    {{$tele->thursday ? "Thursday |" : "" }}
	                    {{$tele->friday ? "Friday |" : "" }}
                    </td>
                </tr>
            </tbody>
            @endforeach

        </table>
    </div>
			
@endif