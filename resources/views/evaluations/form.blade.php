<div class="table-responsive">
    <table class="table table-hover table-condensed">

        <thead>
            <th>Criteria</th>
            <th>Marks</th>   
            <th>Comments</th>
        </thead>

        <tbody>
        	<tr v-for="criterion in criteria">

        		<td class="col-md-2">
                	<span data-toggle="tooltip" data-placement="top" :title="criterion.description" class="glyphicon glyphicon-info-sign"></span> 
                	<span data-toggle="tooltip" data-placement="top" :title="criterion.name + ' (Peso: ' + criterion.percentage +'%)'">@{{ capitalizeFirstLetter(criterion.code) }}</span> 
                </td>

				<td class="col-md-1">
                	<select class="form-control input-sm" :disabled="validateFilter" v-model="criterion.mark"> 
            			<option selected="true" value="">-</option>
						<option v-for="point in criterion.points" :title="point.description" :value="point.value">@{{ point.value }}</option>					
                	</select>
                </td>  

                <td class="col-md-9">		
                    <div class="form-group">
						<textarea class="form-control input-sm" rows="1" placeholder="Comments" v-model="criterion.comment" :disabled="validateFilter"></textarea>
					</div>
				</td>   

            </tr>
        </tbody>
    </table>

</div>