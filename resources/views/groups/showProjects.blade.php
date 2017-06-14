@if(count($project->groups)>0)
	
    <div class="row">
        <div class="col-md-4">
            <div class="panel panel-default">

                <div class="panel-heading"><b>GROUPS</b></div>
                <div class="table-responsive">

                <table class="table">
                    <thead>
                        <th>#</th>
                        <th>Name</th>   
                    </thead>

                    @foreach($project->groups as $group)
                        <tbody>
                            @if($group->enabled)
                                <tr class="success">
                            @else
                                <tr class="danger">
                            @endif  
                                <td>{{ $loop->iteration}}</td>
                                <td>{{ $group->name }}</td>
                            </tr>
                        </tbody>
                    @endforeach

                </table>

                </div>

            </div>    
        </div>  
    </div>



			
@endif