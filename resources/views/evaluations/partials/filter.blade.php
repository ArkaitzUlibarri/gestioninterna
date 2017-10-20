<div class="form-inline pull-right-sm">

    <select title="Year" name="year" class="form-control input-sm" v-model="filter.year" v-on:change="yearChange()">
        <option selected="true" value="{{date('Y')}}">{{ date('Y') }}</option>
        <option value="{{date('Y') - 1}}">{{ date('Y') - 1 }}</option>
        <option value="{{date('Y') - 2}}">{{ date('Y') - 2 }}</option>
    </select>

	<select title="Employee" name="employee" class="form-control input-sm" v-model="filter.employee" v-on:change="employeeChange()">
		<option selected="true" value="">Employee</option>  
		<template v-for="(user, index) in employeeList">
            <option :value="user.id" :user="user" :index="index">@{{user.full_name.toUpperCase()}}</option>
        </template> 
	</select>

	<a title="Export" v-bind:href="makeUrl('{{ url('evaluations/download/') }}', [filter.year, filter.employee])" class="btn btn-success btn-sm custom-btn-width" :disabled="validateExportButton">
         <span class="glyphicon glyphicon-download-alt"></span> Export
    </a>
	
</div>


