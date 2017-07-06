<div class="form-inline">

    <a href="{{ url('validation/download') }}" class="btn btn-success btn-sm custom-btn-width">
        <span class="glyphicon glyphicon-download-alt"></span> Export
    </a>

    <div class="pull-right">
        <select name="year" class="form-control input-sm" v-model="filter.year">
            <option value="2018">2018</option>
            <option value="2017">2017</option>
            <option value="2016">2016</option>
        </select>

        <input type="number"
               name="week"
               min="1"
               max="53"
               style="width: 70px;"
               class="form-control input-sm"
               placeholder="Week number"
               v-model="filter.week">

    <input type="text"
           class="form-control input-sm"
           name="name"
           placeholder="Activity name"
           v-model="filter.activity">

        <input type="text"
               name="user"
               class="form-control input-sm"
               placeholder="Employee name"
               v-model="filter.user">

        <button class="btn btn-default btn-sm custom-btn-width" v-on:click="fetchData">
            <span class="glyphicon glyphicon-filter"></span> Filter
        </button>
    </div>

</div> 