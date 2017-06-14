@if(count($contract->reductions)>0)

	<div class="row">
        <div class="col-md-6">
            <div class="panel panel-default">

                <div class="panel-heading"><b>REDUCTIONS</b></div>
            	<div class="table-responsive">

                    <table class="table">
                        <thead>
                            <th>Start date</th>
                            <th>End date</th>   
                            <th>Week Hours</th>
                        </thead>

                        @foreach($contract->reductions as $reduction)
                        <tbody>
                            <tr>
                                <td>{{$reduction->start_date}}</td>
                                <td>{{$reduction->end_date}}</td>
                                <td>{{$reduction->week_hours}}</td>
                            </tr>
                        </tbody>
                        @endforeach

                    </table>

                </div>

            </div>    
		</div>	
    </div>
        
@endif