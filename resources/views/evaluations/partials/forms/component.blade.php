<div class="panel panel-primary">  
    <div class="panel-heading"> 
        <div class="form-inline pull-left-sm">
            <select title="Month" name="month" class="form-control input-sm" v-model="filter.month" v-on:change="filterProjects()">
                <option selected="true" value="">Month</option> 
                <template v-for="(value, key) in monthList">     
                    <option :value="value" :key="key">@{{key}}</option> 
                </template>     
            </select> 
            {{ $filter }}               
        </div>
        <div class="clearfix"></div>
    </div>
    <div class="panel-body">
        <div class="table-responsive">
            <table class="table table-hover table-condensed" style="margin-bottom: 0px">

                <thead>
                    <th>Criteria</th>
                    <th>Marks</th>   
                    <th>Comments</th>     
                    <th>Actions</th>  
                </thead>

                <tbody>
                    {{ $body }}
                </tbody>

            </table>
        </div>
    </div>  
</div>  