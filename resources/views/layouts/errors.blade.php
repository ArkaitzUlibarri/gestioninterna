@if(count($errors))
	<div class  ="form-group">	
		<div class="alert alert-danger">
			<ul class="list-unstyled">
				@foreach($errors->all() as $error)
					<li><span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span> {{ $error }}</li>
				@endforeach
			</ul>
		</div>
	</div>	
@endif