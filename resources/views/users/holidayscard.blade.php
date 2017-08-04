<div class="table-responsive">
<table class="table">
    <thead>
        <th>Year</th>
        <th>Last Year</th>
        <th>Current Year</th>   
        <th>Extras</th>
        <th>Used Next Year</th>   
    </thead>
    <tbody>
        <tr>              
            <td>{{$usercard->year}}</td>
            <td>{{$usercard->used_last_year}} / {{$usercard->last_year}}</td> 
            <td>{{$usercard->used_current_year}} / {{$usercard->current_year}}</td> 
            <td>{{$usercard->used_extras}} / {{$usercard->extras}}</td> 
            <td>{{$usercard->used_next_year}}</td> 
        </tr>
    </tbody>
</table>
</div>

