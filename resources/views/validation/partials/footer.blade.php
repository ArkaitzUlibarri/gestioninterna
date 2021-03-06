<div class="form-inline">

	<div class="pull-left" v-show="filter.weekType == 'reports'">
		<button title="Previous Week" class="btn btn-default btn-sm" v-on:click="fetchData(parseInt(filter.week) - 1)">
	      <span class="glyphicon glyphicon-arrow-left"></span>
	    </button>
	</div>

	<button title="Key Colors Info" class="btn btn-default btn-sm custom-btn-width col-sm-offset-6" v-on:mouseover="upHere=true" v-on:mouseleave="upHere=false">
      <span class="glyphicon glyphicon-info-sign"></span> Info
    </button>

	<div class="pull-right" v-show="filter.weekType == 'reports'">
	    <button title="Next Week" class="btn btn-default btn-sm" v-on:click="fetchData(parseInt(filter.week) + 1)">
	      <span class="glyphicon glyphicon-arrow-right"></span>
	    </button>
	</div>
</div>