<div class="panel panel-default">
    <div class="panel-heading">
        <strong>CONTRACT DETAILS</strong>
    </div>
    <div class="panel-body">

        <div class="panel panel-default">
            <div class="panel-heading">
                <strong>Main</strong>
            </div>
            <div class="panel-body">
                <p class="col-xs-12 col-sm-4"><strong>Employee: </strong> {{ $contract->user->fullname }}</p>
                <p class="col-xs-12 col-sm-5"><strong>Type of contract: </strong> {{ $contract->contractType->name }}</p>
                <p class="col-xs-12 col-sm-3"><strong>Week working hours: </strong> {{ $contract->week_hours}}</p>
            </div>
        </div>

        <div class="panel panel-default">
            <div class="panel-heading">
                <strong>Dates</strong>
            </div>
            <div class="panel-body">
                <p class="col-xs-12 col-sm-4"><strong>Start date: </strong> {{ $contract->start_date}}</p>
                <p class="col-xs-12 col-sm-4"><strong>Estimated end date: </strong> {{ empty($contract->estimated_end_date) ? "None": $contract->estimated_end_date }}</p>
                <p class="col-xs-12 col-sm-4"><strong>End date: </strong> {{ empty($contract->end_date) ? "In progress" : $contract->end_date}}</p>
            </div>
        </div>

        <div class="panel panel-default">
            <div class="panel-heading">
                <strong>Location</strong>
            </div>
            <div class="panel-body">
                <p class="col-xs-12 col-sm-4"><strong>Country: </strong> {{ $nationalDayName }}</p>
                <p class="col-xs-12 col-sm-4"><strong>Region: </strong> {{ $regionalDayName }}</p>
                <p class="col-xs-12 col-sm-4"><strong>City: </strong> {{ $localDayName }}</p>
            </div>
        </div>

    </div>
</div>