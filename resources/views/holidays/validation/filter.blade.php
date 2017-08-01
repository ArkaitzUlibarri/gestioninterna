<div class="form-inline">

	<div class="pull-left">

	</div>

	<div class="pull-right">

    <select name="year" class="form-control input-sm" v-model="filter.year">
      <option value="2018">2018</option>
      <option value="2017">2017</option>
      <option value="2016">2016</option>
    </select>

    <select name="week_type" class="form-control input-sm" v-model="filter.week_type">
        <option value="all">All Weeks</option>
        <option value="holidays">Holidays weeks</option>
    </select>

    <!--All Weeks-->
    <input type="number" name="week" min="1" max="53" style="width: 70px;" 
      class="form-control input-sm" placeholder="Week number"
      v-model="filter.week" v-show='filter.week_type=="all"'>

    <!--Holidays weeks-->
    <select name="week" class="form-control input-sm" v-show='filter.week_type=="holidays"'>
        <option value="38">38</option>
        <option value="39">39</option>
    </select>

    <select name="project" class="form-control input-sm">
        <option selected value="all">Project</option>
        <option value="ran evo">RAN EVO</option>
        <option value="inwi swap">INWI Swap</option>
    </select>

   	<select name="group" class="form-control input-sm">
        <option selected value="all">Group</option>
        <option value="default">Default</option>
        <option value="inwi swap">INWI Swap</option>
    </select>

    <button class="btn btn-default btn-sm custom-btn-width">
        <span class="glyphicon glyphicon-filter"></span> Filter
    </button>

	</div>

</div>