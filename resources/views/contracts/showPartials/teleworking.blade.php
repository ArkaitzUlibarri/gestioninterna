<div class="panel panel-default">

    <div class="panel-heading"><b>TELEWORKING</b></div>

	<div class="table-responsive">

        <table class="table">
            <thead>
                <th>Start date</th>
                <th>End date</th>   
                <th>Days</th>
            </thead>

            @foreach($contract->teleworking as $tele)
            <tbody>
                <tr class="{{ $tele->end_date ? '' : 'success' }}">
                    <td class="col-md-4">{{$tele->start_date}}</td>
                    <td class="col-md-4">{{$tele->end_date}}</td>
                    <td class="col-md-4">
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

</div>
