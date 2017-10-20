<td class="col-md-2">
    <span :title="criterion.description" class="glyphicon glyphicon-info-sign"></span> 
    <span :title="criterion.name + ' (Peso: ' + criterion.percentage +'%)'">@{{ capitalizeFirstLetter(criterion.code) }}</span> 
</td>

<td class="col-md-1">
    <select class="form-control input-sm" :disabled="validateFilter(criterion)" v-model="criterion.mark"> 
        <option selected="true" value="">-</option>
        <option v-for="point in criterion.points" :title="point.description" :value="point.value">@{{ point.value }}</option>                   
    </select>
</td>  

<td class="col-md-9">                         
    <textarea class="form-control input-sm" rows="1" placeholder="Comments" v-model="criterion.comment" :disabled="validateFilter(criterion)" v-show="validateComments(criterion)"></textarea>             
</td>  

<td class="col-md-1">

    <button title="Save" class="btn btn-primary btn-sm" :disabled="validateSaveButton(criterion)" v-on:click="save(criterion)" v-if="validateDeleteButton(criterion)">    
        <span class="glyphicon glyphicon-floppy-disk"></span> <!--Save-->
    </button>

    <button title="Delete" class="btn btn-danger btn-sm" :disabled="validateDeleteButton(criterion)" v-on:click="erase(criterion)" v-else="validateDeleteButton(criterion)">
         <span class="glyphicon glyphicon-trash"></span> <!--Delete-->
    </button>   

</td> 