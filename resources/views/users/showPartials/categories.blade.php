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
                        <td class="col-md-4">{{$category->name}}</td>
                        <td class="col-md-4">{{$category->code}}</td>
                        <td class="col-md-4">{{$category->description}}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    
</div>