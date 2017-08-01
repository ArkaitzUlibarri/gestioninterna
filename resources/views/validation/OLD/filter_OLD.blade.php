<div class="form-inline pull-right">
            
    <input type="text"
           class="form-control input-sm"
           name="name"
           placeholder="Task name"
           v-model="filter.name">

    <select name="validation" class="form-control input-sm" v-model="filter.validated" v-on:change="fetchData">
        <option value="false" selected>Not Validated</option>
        <option value="true">Validated</option>
    </select>

</div> 