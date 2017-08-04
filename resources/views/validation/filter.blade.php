<div class="form-inline">

    <button title="Key Colors Info" class="btn btn-default btn-sm custom-btn-width" v-on:mouseover="upHere=true" v-on:mouseleave="upHere=false">
      <span class="glyphicon glyphicon-info-sign"></span> Info
    </button>

    <a title="Export" href="{{ url('validation/download') }}" class="btn btn-success btn-sm custom-btn-width">
        <span class="glyphicon glyphicon-download-alt"></span> Export
    </a>

    <div class="pull-right">

        <input title="Activity Filter" 
            type="text"
           class="form-control input-sm"
           style="background-color:#ffcc80;"
           name="name"
           placeholder="Activity name"
           v-model="filter.activity">

        <select title="Year" name="year" class="form-control input-sm" v-model="filter.year">
            <option value="2018">2018</option>
            <option value="2017">2017</option>
            <option value="2016">2016</option>
        </select>

        <input title="Week number"
                type="number"
               name="week"
               min="1"
               max="53"
               style="width: 70px;"
               class="form-control input-sm"
               placeholder="Week number"
               v-model="filter.week">

        <select title="Project" name="project" class="form-control input-sm" v-model= 'filter.project'>
            <option selected value="">Project</option>
        </select>

        <select title="Group" name="group" class="form-control input-sm" v-model= 'filter.group'>
            <option selected value="">Group</option>
        </select>

        <input title="Employee"
                type="text"
               name="user"
               class="form-control input-sm"
               placeholder="Employee name"
               v-model="filter.user">

        <button title="Filter" class="btn btn-default btn-sm custom-btn-width" v-on:click="fetchData()">
            <span class="glyphicon glyphicon-filter"></span> Filter
        </button>

    </div>

</div> 