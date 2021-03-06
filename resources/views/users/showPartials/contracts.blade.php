<div class="panel panel-primary">
    
    <div class="panel-heading">Contracts</div>
	
    <div class="table-responsive">
        <table class="table">
            <thead>
                <th>Type</th>
                <th title="Week working hours">Hours</th>
                <th>Start</th>   
                <th>Estimated end</th>
                <th>End</th>              
            </thead>
            <tbody>
                @foreach($contracts as $contract)
                    <tr class="{{ $contract->end_date ? '' : 'success' }}">
                        <td class="col-md-4">{{ucwords($contract->contractType->name)}}</td>
                        <td class="col-md-2">{{$contract->week_hours}}</td>
                        <td class="col-md-2">{{$contract->start_date}}</td>
                        <td class="col-md-2">{{$contract->estimated_end_date}}</td>
                        <td class="col-md-2">{{$contract->end_date}}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
</div>