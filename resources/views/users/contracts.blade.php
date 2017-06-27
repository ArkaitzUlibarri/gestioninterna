<div class="panel panel-primary">
    
    <div class="panel-heading">Contracts</div>
	
    <div class="table-responsive">
        <table class="table">
            <thead>
                <th>Type</th>
                <th>Start date</th>   
                <th>Estimated end date</th>
                <th>End date</th>
                <th>Week working hours</th>
            </thead>
            <tbody>
                @foreach($contracts as $contract)
                <tr class="{{ $contract->end_date ? 'danger' : 'success' }}">
                    <td>{{$contract->contractType->name}}</td>
                    <td>{{$contract->start_date}}</td>
                    <td>{{$contract->estimated_end_date}}</td>
                    <td>{{$contract->end_date}}</td>
                    <td>{{$contract->week_hours}}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
</div>