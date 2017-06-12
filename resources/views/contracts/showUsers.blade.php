@if(count($contracts)>0)
    <div class="row">
        <div class="col-md-8">
        	<div class="panel panel-default">

                <div class="panel-heading"><b>CONTRACTS</b></div>

            	<div class="table-responsive">

                    <table class="table">
                        <thead>
                            <th>Type</th>
                            <th>Start date</th>   
                            <th>Estimated end date</th>
                            <th>End date</th>
                            <th>Week working hours</th>
                        </thead>

                        @foreach($contracts as $contract)
                        <tbody>
                            <tr>
                                <td>{{$contract->contractType->name}}</td>
                                <td>{{$contract->start_date}}</td>
                                <td>{{$contract->estimated_end_date}}</td>
                                <td>{{$contract->end_date}}</td>
                                <td>{{$contract->week_hours}}</td>
                            </tr>
                        </tbody>
                        @endforeach

                    </table>
                </div>

        	</div>	
        </div>
    </div>	
@endif