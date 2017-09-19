<div class="pull-left">
    <button title="Clear" class="btn btn-primary btn-sm" :disabled="validateClearButton" v-on:click="clear()">
		<span class="glyphicon glyphicon-erase"></span> Clear
	</button>
</div>

<div class="pull-right">	
	<button title="Delete" class="btn btn-danger btn-sm" :disabled="validateDeleteButton" v-on:click="erase()">
		<span class="glyphicon glyphicon-trash"></span> Delete
	</button>	

	<button title="Save" class="btn btn-primary btn-sm" :disabled="validateSaveButton" v-on:click="save()">
		<span class="glyphicon glyphicon-floppy-disk"></span> Save
	</button>
</div>