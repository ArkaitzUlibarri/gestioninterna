<!--TOTAL PROYETO-->
<div class="panel panel-primary">

   	<div class="panel-heading form-inline">
		<h3 class="panel-title">@{{getEmployee()}} - @{{getProject()}} - @{{getHours(true)}} hours (@{{getHours(false)}}%)<span class="panel-title pull-right">@{{filter.year}}</span></h3> 
    </div>

    <div class="panel-body">
    	<div class="table-responsive">
	        <table class="table table-condensed">

	            <thead>
	                <th>Criteria</th>
	                <!--Meses-->
				    <template v-for="(value, key) in monthList">     
						<th :title="key" v-bind:class="monthStyle(value)">@{{key.slice(0,3)}}</th> 
					</template>  
					<!--Meses--> 
	                <th title="Total" class="active">Total</th>
	            </thead>

	            <tbody>
	            	@foreach (config('options.performance_evaluation') as $key => $options)
		                <tr>
		                    <td class="col-md-2">
		                    	{{ ucwords($key) }}
		                    </td>

		                    <!--Meses-->  
					        <template v-for="(value, key) in monthList">     
								<td v-bind:class="monthStyle(value)">1</td> 
							</template>   
							<!--Meses-->

		                    <td class="col-md-1 active">2</td>     
		                </tr>
		            @endforeach
	            </tbody>

	        </table>
	    </div>
    </div>

   	<div class="panel-footer">
	  	<b>TOTAL: 5,6</b>
	</div>

</div>