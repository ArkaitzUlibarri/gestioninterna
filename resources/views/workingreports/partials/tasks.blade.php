<div class="panel panel-primary">

	<div class="panel-heading">Tasks</div>

	  <div class="panel-body">
			<div class="row">
				<div class="col-xs-12">
					<span v-for="(task, index) in tasks">
						<task-template :task="task" :index="index" :prop="true" v-if="editIndex == index"></task-template>
						<task-template :task="task" :index="index" :prop="false" v-else></task-template>
					</span>
				</div>
			</div>
	  </div>

	  <div class="panel-footer">
	  		<b>TOTAL HOURS: @{{totalTime}}</b>
	  </div>
</div>