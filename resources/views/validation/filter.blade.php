<div class="form-inline">

    <div class="pull-left">

        <select title="Week Type" name="weekType" class="form-control input-sm" v-model="filter.weekType" v-on:change="loadPendingUsers()">
          <option value="reports">Reports</option>
          <option value="holidays">Holidays</option>
        </select>

        <select title="Users Pending validation" name="pending" class="form-control input-sm" 
          v-show="filter.weekType == 'holidays'" 
          v-model= 'filter.pendingUser' 
          v-on:change="getWeeksUser()" >
            <option value="">User</option>
            <template v-for="(user, index) in userList">
                <option :value="user.user_id" :user="user" :index="index">@{{user.user_name.toUpperCase()}} (@{{user.count}})</option>
            </template> 
        </select>

        <select title="Year" name="year" class="form-control input-sm" v-model="filter.year" v-on:change="loadHolidays()">
            <option value="{{date('Y') + 1}}">{{ date('Y') + 1 }}</option>
            <option value="{{date('Y')}}">{{ date('Y') }}</option>
            <option value="{{date('Y') - 1}}">{{ date('Y') - 1 }}</option>
        </select>

         <input title="Week number"
           type="number"
           name="week"
           min="1"
           max="53"
           style="width: 70px;"
           class="form-control input-sm"
           placeholder="Week number"
           v-model="filter.week"
           v-show="filter.weekType == 'reports'">

        <select title="Week number" name="week" class="form-control input-sm" v-show="filter.weekType == 'holidays'" v-model= 'filter.week'>
            <option selected value="">Week</option>
            <template v-for="(week, index) in weekList">
                <option :week="week" :index="index">@{{week}}</option>
            </template> 
        </select>

        <!--No hay para user/tools-->
        @if (Auth::user()->primaryRole() == 'manager' || Auth::user()->primaryRole() == 'admin')
          <select title="Project" name="project" class="form-control input-sm" v-model= 'filter.project' v-on:change="groupsRefresh()">
              <option selected value="">Project</option>
              <template v-for="(project, index) in projectList">
                  <option :project="project" :index="index">@{{project}}</option>
              </template> 
          </select>

          <select title="Group" name="group" class="form-control input-sm" v-model= 'filter.group'>
              <option selected value="">Group</option>
              <template v-for="(group, index) in groupList">
                  <option :group="group" :index="index">@{{group}}</option>
              </template> 
          </select>

          <input title="Employee"
            type="text"
            name="user"
            class="form-control input-sm"
            placeholder="Employee name"
            v-model="filter.user"
            v-show="filter.weekType == 'reports'">
        @endif
        <!--No hay para user/tools-->

        <button title="Filter" class="btn btn-default btn-sm custom-btn-width" v-on:click="loadData()">
            <span class="glyphicon glyphicon-search"></span> Search
        </button>

    </div>

    <div class="pull-right">

      <div class="input-group">
          <input title="Activity Filter" 
            type="text"
            class="form-control input-sm"
            name="name"
            placeholder="Activity name"
            v-model="filter.activity">
          <span class="input-group-addon input-sm">
            <span class="glyphicon glyphicon-filter"></span> Filter
          </span>
      </div>

      <a title="Export" href="{{ url('validation/download') }}" class="btn btn-success btn-sm custom-btn-width">
          <span class="glyphicon glyphicon-download-alt"></span> Export
      </a>

    </div>

</div> 