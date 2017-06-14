@if(count($groups)>0)

    <div class="row">
        <div class="col-md-8">
            <div class="panel panel-default">

                <div class="panel-heading"><b>PROJECTS/GROUPS</b></div>
	
            	<div class="table-responsive">

                    <table class="table">

                        <thead>
                            <th>Project</th>
                            <th>Group</th>   
                        </thead>

                        @foreach($groups as $group)
                        <tbody>
                            @if($group->enabled)
                                <tr class="success">
                            @else
                                <tr class="danger">
                            @endif                  
                                <td>{{$group->project->name}}</td>      
                                <td>{{$group->name}}</td>  
                            </tr>
                        </tbody>
                        @endforeach

                    </table>
                    
                </div>

            </div>
        </div>        
	</div>	

@endif