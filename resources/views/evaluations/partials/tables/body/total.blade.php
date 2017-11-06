<tr class="active">
    <td class="col-md-2"><b>Hours</b></td> 
    <td v-for="(month_id, month_name) in monthList"><b>@{{ getTotalHours(month_id) }}</b></td> 
    <td class="col-md-1"><b>@{{ getTotalHours() }}</b></td>
</tr>

<tr v-for="criterion in generalform">
    <td class="col-md-2" :title="criterion.name + ' (Peso: ' + criterion.percentage +'%)'">@{{capitalizeFirstLetter(criterion.code)}}</td>  
	<td v-for="(month_id, month_name) in monthList" :class="cellStyleTotal(criterion.code + '|' + month_id, false)">
		@{{ getMarkComment(criterion.code + '|' + month_id, 'mark',true) }}
	</td> 
    <td class="col-md-1" :class="cellStyleTotal(criterion.code, true)">@{{ getTotalColumn(criterion.code, true) }}</td>     
</tr>

<tr v-for="criterion in knowledgeform">
    <td class="col-md-2" :title="criterion.name + ' (Peso: ' + criterion.percentage +'%)'">@{{capitalizeFirstLetter(criterion.code)}}</td>  
	<td v-for="(month_id, month_name) in monthList" :class="cellStyleTotal(criterion.code + '|' + month_id, false)" :title="getMarkComment(criterion.code + '|' + month_id, 'description', true)">
		@{{ getMarkComment(criterion.code + '|' + month_id, 'mark',true) }}
	</td> 
    <td class="col-md-1" :class="cellStyleTotal(criterion.code, true)">@{{ getTotalColumn(criterion.code, true) }}</td>     
</tr>

<tr class="info">
    <td class="col-md-2"><b>TOTAL (%)</b></td> 
    <td v-for="(month_id, month_name) in monthList"></td> 
    <td class="col-md-1"><b>@{{ getTotalValue("", true) }}</b></td>
</tr> 