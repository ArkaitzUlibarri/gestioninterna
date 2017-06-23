<div class="panel panel-default">
    <div class="panel-body">
        <p class="col-xs-12 col-sm-6"><strong>Project name: </strong> {{ strtoupper($project->name) }}</p>
        <p class="col-xs-12 col-sm-6"><strong>Customer: </strong> {{ strtoupper($project->customer->name) }}</p>
        <p class="col-xs-12 col-sm-6"><strong>Start date: </strong> {{ $project->start_date }}</p>
        <p class="col-xs-12 col-sm-6"><strong>End date: </strong> {{empty($project->end_date) ? "In progress" : $project->end_date}}</p>
        <p class="col-xs-12 col-sm-6"><strong>Project Manager: </strong> {{ ucfirst($project->pm->fullname) }}</p>
    </div>
</div>

<div class="panel panel-default">
    <div class="panel-heading"><strong>Description</strong></div>
    <div class="panel-body">
        {{ $project->description }}
    </div>
</div>