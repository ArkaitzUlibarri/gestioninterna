<div class="panel panel-default">
    
    <div class="panel-heading">
        <strong>PROJECTS &amp; GROUPS</strong>
    </div>
	
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