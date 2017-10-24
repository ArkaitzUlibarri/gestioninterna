<tr class="active">
	<td class="col-md-2"><b>Hours</b></td>
	<td v-for="(month_id, month_name) in monthList"><b>@{{ getHours(month_id,element.project_id,'option') }}</b></td>
	<td class="col-md-1"></td>
</tr> 

<tr v-for="criterion in generalform">
    <td class="col-md-2" :title="criterion.name + ' (Peso: ' + criterion.percentage +'%)'">
    	@{{capitalizeFirstLetter(criterion.code)}}
    </td> 
	<td v-for="(month_id, month_name) in monthList" 
		:class="cellStyleProject(element.project_id + '|' + criterion.code + '|' + month_id, false)" 
		:title="getMarkComment(element.project_id + '|' + criterion.code + '|' + month_id, 'description', false)">
		@{{ getMarkComment(element.project_id + '|' + criterion.code + '|' + month_id, 'mark', false) }}
	</td> 
    <td class="col-md-1" :class="cellStyleProject(element.project_id + '|' + criterion.code, true)" >
    	@{{ getTotalColumn(element.project_id + '|' + criterion.code, false) }}
   	</td>     
</tr> 

<tr class="active">
	<td class="col-md-2"><b>Weight (%)</b></td>
	<td v-for="(month_id, month_name) in monthList"><b>@{{ getHours(month_id,element.project_id) }}</b></td>
	<td class="col-md-1"></td>
</tr>  
	                            
