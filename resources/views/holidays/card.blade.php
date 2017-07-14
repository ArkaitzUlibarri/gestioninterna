<div class="col-sm-12">
    <div class="panel panel-primary">
        <div class="panel-heading"> <strong>{{ucwords(Auth::user()->full_name)}} - @{{year}}</strong></div>
        <div class="panel-body">


                 <div class="col-sm-6">
                    <p><strong>Holidays</strong></p>
                    <p>Current Year: @{{ userCard.current_year }}</p>
                    <p>Last Year: @{{ userCard.last_year }}</p>
                    <p>Extras: @{{ userCard.extras }}</p>
                    <p>Total: @{{ userCard.total }}</p>
                </div>

                <div class="col-sm-6">
                    <p><strong>Consumed</strong></p>
                    <p>Current Year: @{{ userCard.used_current_year }}</p>
                    <p>Last Year: @{{ userCard.used_last_year }}</p>
                    <p>Extras: @{{ userCard.used_extras }}</p>
                    <p>Total: @{{ userCard.used_total }}</p>
                </div>


        </div>
    </div>
</div>