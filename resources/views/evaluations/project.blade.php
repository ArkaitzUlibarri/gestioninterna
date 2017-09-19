<div class="panel panel-primary">

   	<div class="panel-heading form-inline">
		<h3 class="panel-title">@{{getEmployee()}} - @{{getProject()}} - @{{getHours(true)}} hours (@{{getHours(false)}}%)<span class="panel-title pull-right">@{{filter.year}}</span></h3> 
    </div>

    <div class="panel-body">
    	<div class="table-responsive">
	        <table class="table table-condensed">

	            <thead>
	                <th>Criteria</th>                
					<th v-for="(value, key) in monthList" :title="key" v-bind:class="monthStyle(value)">@{{key.slice(0,3)}}</th>
	                <th title="Total" class="danger">Total</th>
	            </thead>

	            <tbody>    
	                <tr v-for="criterion in criteria">
	                    <td class="col-md-2">@{{capitalizeFirstLetter(criterion.code)}}</td> 
						<td v-for="(value, key) in monthList" v-bind:class="monthStyle(value)" :title="getMarkComment(criterion.code + '|' + value, 'comment')">
							@{{ getMarkComment(criterion.code + '|' + value, 'mark') }}
						</td> 
	                    <td class="col-md-1 danger">@{{ getTotal(criterion.code) }}</td>     
	                </tr>             
	            </tbody>

	        </table>
	    </div>
    </div>

   	<div class="panel-footer">
	  	<b>TOTAL: @{{ getProjectTableTotal() }}</b>
	</div>

</div>