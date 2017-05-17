@if(count($project->groups)>0)
	
		<h4>GROUPS</h4>

        <table class="table table-hover">

            @foreach($project->groups as $group)
            <dl class="dl-horizontal">
                <dt>{{ $loop->iteration}}</dt>
                <dd><b>{{ $group->name }}</b> {{ $group->enabled ? '(Enabled)' : '(Disabled)' }} </dd>
            </dl>
            @endforeach

        </table>
			
@endif