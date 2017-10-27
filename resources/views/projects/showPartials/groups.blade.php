<div class="panel panel-primary">
    <div class="panel-heading">Groups</div>
    <div class="table-responsive">
        <table class="table">
        <thead>
            <th>#</th>
            <th>Name</th>
        </thead>    
        <tbody>
            @foreach($project->groups as $group)
                <tr class="{{ $group->enabled ? 'success' : '' }}">
                    <td class="col-md-2">{{ $loop->iteration}}</td>
                    <td class="col-md-10">{{ ucwords($group->name) }}</td>
                </tr>
            @endforeach
        </tbody>
        </table>
    </div>
</div>