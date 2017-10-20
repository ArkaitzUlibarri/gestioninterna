<select title="Project" name="project" class="form-control input-sm" v-model="filter.project" v-on:change="projectChange()">
    <option selected="true" value="">Project</option>  
    <template v-for="(project, index) in projectList">
        <option :value="project.id" :project="project" :index="index">@{{project.name}}</option>
    </template>               
</select>  