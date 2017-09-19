<!--TOTAL-->
<div class="panel panel-primary">

   	<div class="panel-heading form-inline">
		<h3 class="panel-title">@{{getEmployee()}} - TOTAL <span class="panel-title pull-right">@{{filter.year}}</span></h3> 
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
						<td v-for="(value, key) in monthList" v-bind:class="monthStyle(value)">1</td> 
	                    <td class="col-md-1 danger">1</td>     
	                </tr>
	            </tbody>

	        </table>
	    </div>
    </div>

   	<div class="panel-footer">
	  	<b>TOTAL: <span style="color:red;">5,6</span></b>
	</div>

</div>