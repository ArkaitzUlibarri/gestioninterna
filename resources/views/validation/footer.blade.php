<div class="form-inline">
	<div class="pull-left">
		<button class="btn btn-default btn-sm" v-on:click="fetchData(parseInt(filter.week) - 1)">
	      <span class="glyphicon glyphicon-arrow-left"></span>
	    </button>
	</div>
	<div class="pull-right">
	    <button class="btn btn-default btn-sm" v-on:click="fetchData(parseInt(filter.week) + 1)">
	      <span class="glyphicon glyphicon-arrow-right"></span>
	    </button>
	</div>
</div>