<div class="panel panel-primary">
    <div class="panel-heading"> <strong>{{ucwords(Auth::user()->full_name)}} - @{{actualYear}}</strong></div>
    <div class="panel-body">

        <table class="table table-condensed table-borderless"  style="margin-bottom:0px">
  
            <thead>
                <th>Type</th>
                <th title="Consumed/Holidays">C/H</th>
            </thead>
        
            <tbody>
                <tr>
                    <td class="col-md-9">Last Year Holidays</td> 
                    <td class="col-md-3">@{{ userCard.used_last_year }} / @{{ userCard.last_year }}</td>
                </tr>  
                 <tr>
                    <td class="col-md-9">Holidays</td> 
                    <td class="col-md-3">@{{ userCard.used_current_year }} / @{{ userCard.current_year }}</td>
                </tr>
                 <tr>
                    <td class="col-md-9">Extra Holidays</td> 
                    <td class="col-md-3">@{{ userCard.used_extras }} / @{{ userCard.extras }}</td>
                </tr>   
                <tr class="active">
                    <td class="col-md-9">Next Year</td> 
                    <td class="col-md-3">@{{ userCard.used_next_year }}</td>
                </tr>
            </tbody>
                               
        </table>

    </div>

    <div class="panel-footer">  
        <table class="table table-condensed table-borderless" style="margin-bottom:0px"> 
            <tbody>
                <tr>
                    <td class="col-md-9"><b>TOTAL</b></td> 
                    <td class="col-md-3"><b>@{{ userCard.used_total }} / @{{ userCard.total }}</b></td>
                </tr>  
            </tbody>                             
        </table>  
    </div>

</div>
