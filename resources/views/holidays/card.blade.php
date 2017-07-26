<div class="panel panel-primary">
    <div class="panel-heading"> <strong>{{ucwords(Auth::user()->full_name)}} - @{{actualYear}}</strong></div>
    <div class="panel-body">

        <table class="table table-condensed table-borderless">
  
            <thead>
                <th>Type</th>
                <th title="Consumed/Holidays">C/H</th>
            </thead>
        
            <tbody>
                 <tr>
                    <td>Holidays</td> <td>@{{ userCard.used_current_year }} / @{{ userCard.current_year }}</td>
                </tr>
                 <tr>
                    <td>Last Year Holidays</td> <td>@{{ userCard.used_last_year }} / @{{ userCard.last_year }}</td>
                </tr>   
                 <tr>
                    <td>Extra Holidays</td> <td>@{{ userCard.used_extras }} / @{{ userCard.extras }}</td>
                </tr>   
                 <tr>
                    <td>Total</td> <td>@{{ userCard.used_total }} / @{{ userCard.total }}</td>
                </tr>
                <tr>
                    <td>Next Year</td> <td>@{{ userCard.used_next_year }}</td>
                </tr>
            </tbody>
                               
        </table>

    </div>
</div>
