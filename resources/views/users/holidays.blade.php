<div class="panel panel-primary">
    
    <div class="panel-heading">Holidays</div>

    @if (count($usercard) > 0)
        @include('users.holidayscard')
    @endif
	
    <div class="table-responsive">
        <table class="table">
            <thead>
                <th>Date</th>
                <th>Type</th>   
                <th>Validation</th>  
            </thead>
            <tbody>
                @foreach($holidays as $holiday)
                    <tr class="{{ $holiday->validated ? 'success' : 'default' }}">              
                        <td>{{ $holiday->date }}</td>
                        <td>{{ ucwords($holiday->type) }}</td> 
                        <td>{{ $holiday->validated ? "Validated" : "Pending Validation" }}</td> 
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

</div>