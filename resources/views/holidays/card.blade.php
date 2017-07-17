<div class="col-sm-2 col-sm-offset-2">
    <div class="panel panel-primary">
        <div class="panel-heading"> <strong>{{ucwords(Auth::user()->full_name)}} - @{{year}}</strong></div>
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
                </tbody>
                                   
            </table>

        </div>
    </div>
</div>