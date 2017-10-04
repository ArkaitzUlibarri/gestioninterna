<div class="panel panel-primary">
    <div class="panel-heading">Profile</div>
    <div class="panel-body">
        <p class="col-xs-12 col-sm-12"><strong>Project name: </strong> {{ ucwords($project->name) }}</p>
        <p class="col-xs-12 col-sm-6"><strong>Customer: </strong> {{ strtoupper($project->customer->name) }}</p>
        <p class="col-xs-12 col-sm-6"><strong>Project Manager: </strong> {{ ucwords($project->pm->fullname) }}</p>
        <p class="col-xs-12 col-sm-6"><strong>Start date: </strong> {{ $project->start_date }}</p>
        <p class="col-xs-12 col-sm-6"><strong>End date: </strong> {{empty($project->end_date) ? "In progress" : $project->end_date}}</p>
    </div>
</div>

<div class="panel panel-primary">
    <div class="panel-heading">Description</div>
    <div class="panel-body">
        {{ ucfirst($project->description) }}
    </div>
</div>