@if(count($categories)>0)
	
	<div class="table-responsive">

		<h4>CATEGORIES</h4>

        <table class="table table-hover">
            <thead>
                <th>Name</th>
                <th>Code</th>   
                <th>Description</th>
            </thead>

            @foreach($categories as $category)
            <tbody>
                <tr>
                    <td>{{$category->name}}</td>
                    <td>{{$category->code}}</td>
                    <td>
	                    {{$category->description}}
                    </td>
                </tr>
            </tbody>
            @endforeach

        </table>
    </div>
			
@endif