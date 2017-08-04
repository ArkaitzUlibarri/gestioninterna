<div class="form-inline">

	<div class="pull-left">

	</div>

	<div class="pull-right">

    <select title="Year" name="year" class="form-control input-sm" v-model="filter.year" v-on:change="loadWeeks">
      <option value="2019">2019</option>
      <option value="2018">2018</option>
      <option value="2017">2017</option>
    </select>
<!--***Weeks***-->
    <select title="Week type" name="week_type" class="form-control input-sm" v-model="filter.week_type">
        <option value="all">All Weeks</option>
        <option value="holidays">Holidays weeks</option>
    </select>

    <!--All Weeks-->
    <input title="Week number" type="number" name="week" min="1" max="53" style="width: 70px;" 
      class="form-control input-sm" placeholder="Week number"
      v-model="filter.week" v-if='filter.week_type=="all"'>

    <!--Holidays weeks-->
    <select title="Week number" name="week" class="form-control input-sm" v-model="filter.week" v-else='filter.week_type=="holidays"'>
        <option selected value="">Week</option>
        <template v-for="(week, index) in weekList">
            <option :week="week.weekdate" :index="index">@{{week.weekdate}}</option>
        </template> 
    </select>
<!--***Weeks***-->
    <select title="Project" name="project" class="form-control input-sm" v-model= 'filter.project' v-on:change="groupsRefresh()">
        <option selected value="">Project</option>
        <template v-for="(project, index) in projectList">
            <option :project="project" :index="index">@{{project}}</option>
        </template> 
    </select>

   	<select title="Group" name="group" class="form-control input-sm" v-show="filter.project != ''" v-model= 'filter.group'>
        <option selected value="">Group</option>
        <template v-for="(group, index) in groupList">
            <option :group="group" :index="index">@{{group}}</option>
        </template> 
    </select>

    <button title="Filter" class="btn btn-default btn-sm custom-btn-width" v-on:click= 'fetchData()'>
        <span class="glyphicon glyphicon-filter"></span> Filter
    </button>

	</div>

</div>