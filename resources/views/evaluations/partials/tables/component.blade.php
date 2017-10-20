<div class="panel panel-primary">

   	<div class="panel-heading form-inline">
		<h3 class="panel-title">{{ $title }}
			<span class="panel-title pull-right">@{{filter.year}}</span>
		</h3> 
    </div>

    <div class="panel-body">
    	<div class="table-responsive">
	        <table class="table table-condensed" style="margin-bottom: 0px">

	            <thead>
	                <th>Criteria</th>
					<th v-for="(month_id, month_name) in monthList" :title="month_name">@{{month_name.slice(0,3)}}</th> 
	                {{ $column }}
	            </thead>

	            <tbody>
	            	{{ $body }}
	            </tbody>

	        </table>
	    </div>
    </div>

</div>