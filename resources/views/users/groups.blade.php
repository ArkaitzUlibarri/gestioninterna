@if(count($groups)>0)
	
	<div class="table-responsive">

		<h4>PROJECTS/GROUPS</h4>

        <table class="table table-hover">

            <thead>
                <th>Project</th>
                <th>Group</th>   
            </thead>

            @foreach($groups as $group)
            <tbody>
                <tr>                
                    <td>{{$group->project->name}}</td>      
                    <td>{{$group->name}}</td>  
                </tr>
            </tbody>
            @endforeach

        </table>
    </div>
			
@endif