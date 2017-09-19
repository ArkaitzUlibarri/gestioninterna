<div class="form-inline pull-right-sm">

    <select title="Year" name="year" class="form-control input-sm" v-model="filter.year">
        <option selected="true" value="{{date('Y')}}">{{ date('Y') }}</option>
        <option value="{{date('Y') - 1}}">{{ date('Y') - 1 }}</option>
        <option value="{{date('Y') - 2}}">{{ date('Y') - 2 }}</option>
    </select>

	<select title="Month" name="month" class="form-control input-sm" v-model="filter.month" v-on:change="fetchEmployees()">
		<option selected="true" value="">Month</option> 
		<template v-for="(value, key) in monthList">     
			<option :value="value" :key="key">@{{key}}</option> 
		</template>     
	</select>
        
	<select title="Employee" name="employee" class="form-control input-sm" v-model="filter.employee" v-on:change="fetchProjects()">
		<option selected="true" value="">Employee</option>  
		<template v-for="(user, index) in employeeList">
            <option :value="user.id" :user="user" :index="index">@{{user.full_name.toUpperCase()}}</option>
        </template> 
	</select>

	<select title="Project" name="project" class="form-control input-sm" v-model="filter.project" v-on:change="projectChange()">
		<option selected="true" value="">Project</option>  
		<template v-for="(project, index) in projectList">
            <option :value="project.id" :project="project" :index="index">@{{project.name}}</option>
        </template>               
	</select>

	<a title="Export" href="" class="btn btn-success btn-sm custom-btn-width" :disabled="validateFilter">
         <span class="glyphicon glyphicon-download-alt"></span> Export
    </a>
	
</div>


