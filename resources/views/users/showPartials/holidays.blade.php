<div class="panel panel-primary">
    
    <div class="panel-heading">Holidays</div>
	
    <div class="table-responsive">
        <table class="table">
            <thead> 
                <th>Type</th>    
                <th>Date</th>
            </thead>
            <tbody>
                @foreach($holidays as $holiday)
                    <tr class="{{ $holiday->validated ? 'info' : '' }}">              
                        <td class="col-md-8">{{ ucwords($holiday->type) }}</td>
                        <td class="col-md-4">{{ $holiday->date }}</td> 
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    @if (count($usercard) > 0)
        <div class="panel-footer">
            <p class="col-xs-12 col-sm-2"><strong>{{$usercard->year}} </strong></p>
            <p class="col-xs-12 col-sm-3"><strong>Last Year: </strong> {{$usercard->used_last_year}} / {{$usercard->last_year}}</p>
            <p class="col-xs-12 col-sm-3"><strong>Current Year: </strong> {{$usercard->used_current_year}} / {{$usercard->current_year}}</p>
            <p class="col-xs-12 col-sm-3"><strong>Extras: </strong> {{$usercard->used_extras}} / {{$usercard->extras}}</p>

            <p class="col-xs-12 col-sm-2"><strong>{{ $usercard->year +1 }} </strong></p>
            <p class="col-xs-12 col-sm-3"><strong>Used Next Year: </strong> {{$usercard->used_next_year}}</p>
            <div class="clearfix"></div>
        </div>
    @endif

</div>