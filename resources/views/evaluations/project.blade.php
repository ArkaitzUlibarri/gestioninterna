<div class="panel panel-primary">

   	<div class="panel-heading form-inline">
		<h3 class="panel-title">@{{getEmployee()}} - @{{element.project}} - @{{element.hours}} hours (@{{getHours(false,element.project_id)}}%)
			<span class="panel-title pull-right">@{{filter.year}}</span>
		</h3> 
    </div>

    <div class="panel-body">
    	<div class="table-responsive">
	        <table class="table table-condensed" style="margin-bottom: 0px">
	            <thead>
	                <th>Criteria</th>                
					<th v-for="(month_id, month_name) in monthList" :title="month_name" v-bind:class="monthStyle(month_id)">@{{month_name.slice(0,3)}}</th>
	                <th title="Average" class="info">Average</th>
	            </thead>
	            <tbody>    
	                <tr v-for="criterion in criteria">
	                    <td class="col-md-2" :title="criterion.name + ' (Peso: ' + criterion.percentage +'%)'">
	                    	@{{capitalizeFirstLetter(criterion.code)}}
	                    </td> 
						<td v-for="(month_id, month_name) in monthList" 
							:class="cellStyleProject(element.project_id + '|' + criterion.code + '|' + month_id, false)" 
							:title="getMarkComment(element.project_id + '|' + criterion.code + '|' + month_id, 'description', false)">
							@{{ getMarkComment(element.project_id + '|' + criterion.code + '|' + month_id, 'mark', false) }}
							<span v-if="getMarkComment(element.project_id + '|' + criterion.code + '|' + month_id, 'weight',false) != ''">
								(@{{ getMarkComment(element.project_id + '|' + criterion.code + '|' + month_id, 'weight',false) }}%)
							</span>
						</td> 
	                    <td class="col-md-1" :class="cellStyleProject(element.project_id + '|' + criterion.code, true)" 
	                    	>@{{ getTotalColumn(element.project_id + '|' + criterion.code, false) }}
	                   	</td>     
	                </tr>             
	            </tbody>
	        </table>
	    </div>
    </div>
	
</div>