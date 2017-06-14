@if(count($contract->teleworking)>0)

	<div class="row">
        <div class="col-md-6">
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

            </div>
		</div>	
    </div>    

@endif