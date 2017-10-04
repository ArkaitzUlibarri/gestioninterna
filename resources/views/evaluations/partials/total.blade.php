<div class="panel panel-primary">

   	<div class="panel-heading form-inline">
		<h3 class="panel-title">@{{getEmployee()}} | TOTAL |
			<span class="panel-title pull-right">@{{filter.year}}</span>
		</h3> 
    </div>

    <div class="panel-body">
    	<div class="table-responsive">
	        <table class="table table-condensed" style="margin-bottom: 0px">

	            <thead>
	                <th>Criteria</th>
					<th v-for="(month_id, month_name) in monthList" :title="month_name" v-bind:class="monthStyle(month_id)">@{{month_name.slice(0,3)}}</th> 
	                <th title="Total" class="info">Total</th>
	            </thead>

	            <tbody>
	                <tr v-for="criterion in criteria">
	                    <td class="col-md-2" :title="criterion.name + ' (Peso: ' + criterion.percentage +'%)'">@{{capitalizeFirstLetter(criterion.code)}}</td>  
						<td v-for="(month_id, month_name) in monthList" :class="cellStyleTotal(criterion.code + '|' + month_id, false)">
							@{{ getMarkComment(criterion.code + '|' + month_id, 'mark',true) }}
						</td> 
	                    <td class="col-md-1" :class="cellStyleTotal(criterion.code, true)">@{{ getTotalColumn(criterion.code, true) }}</td>     
	                </tr>
	                <tr class="active">
	                    <td class="col-md-2"><b>TOTAL (%)</b></td> 
	                    <td v-for="(month_id, month_name) in monthList"></td> 
	                    <td class="col-md-1"><b>@{{ getTotalValue("", true) }}</b></td>
                	</tr> 
	            </tbody>

	        </table>
	    </div>
    </div>

</div>