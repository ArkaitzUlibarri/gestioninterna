@if(count($categories)>0)
	<div class="row">
        <div class="col-md-8">
            <div class="panel panel-default">

                <div class="panel-heading"><b>CATEGORIES</b></div>

            	<div class="table-responsive">

                    <table class="table">
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

            </div>
        </div>
    </div>			
@endif