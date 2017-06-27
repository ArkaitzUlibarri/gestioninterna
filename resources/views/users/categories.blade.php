<div class="panel panel-primary">
    
    <div class="panel-heading">Categories</div>
	
    <div class="table-responsive">
        <table class="table">
            <thead>
                <th>Name</th>
                <th>Code</th>   
                <th>Description</th>
            </thead>
            <tbody>
                @foreach($categories as $category)
                    <tr>
                        <td>{{$category->name}}</td>
                        <td>{{$category->code}}</td>
                        <td>{{$category->description}}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
</div>