@if(count($contract->reductions)>0)
	
	<div class="table-responsive col-sm-6">

		<h4>REDUCTIONS</h4>

        <table class="table table-hover ">
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
			
@endif