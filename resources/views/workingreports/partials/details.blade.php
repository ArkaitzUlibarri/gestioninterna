<div class="panel panel-primary" style="margin-top: 1em">
	<div class="panel-body">
		<div class="form-inline">
			
			<span style="padding-right: 1em">
				<strong>{{ ucwords($reportUser->fullname) }}</strong>
			</span>

			Report Day: 
			<input type ="date"
				   id="datefield"
				   name="created_at"
				   class="form-control input-sm"
				   style="margin-right: .5em;"
				   min="2017-01-01"
				   placeholder="yyyy-mm-dd"
				   v-model="reportdate"
				   v-on:blur="dateValidation">

			W@{{ week }}-@{{ reportDayWeek.toUpperCase() }}

			<button class="btn btn-primary btn-sm pull-right"
					v-on:click="copyTasks"
					v-bind:disabled="tasks.length != 0">
				Copy last report
			</button>

		</div>
	</div>	
</div>