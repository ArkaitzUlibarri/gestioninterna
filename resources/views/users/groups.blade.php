<div class="panel panel-primary">
    
    <div class="panel-heading">Projects &amp; Groups</div>
	
    <div class="table-responsive">
        <table class="table">
            <thead>
                <th>Project</th>
                <th>Group</th>   
            </thead>
            <tbody>
                @foreach($groups as $group)
                    <tr class="{{ $group->enabled ? 'success' : 'danger' }}">              
                        <td>{{ $group->project->name }}</td>
                        <td>{{ $group->name }}</td> 
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

</div>