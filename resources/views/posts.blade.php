<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>All Posts</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
</head>
<body>
	<section style="padding-top: 60px;">
		<div class="container">
			<div class="row">
				<div class="col-md-12">
					<div class="card">
						<div class="card-header">
							All Posts <a href="/add-post" class="btn btn-success">Add New Post</a>
						</div>
						<div class="card-body">
							@if(Session::has('post_deleted'))
								<div class="alert alert-success" role="alert">
									{{Session::get('post_deleted')}}
								</div>
							@endif
							<table class="table table-striped">
								<thead>
									<tr>
										<th>ID</th>
										<th>Post Title</th>
										<th>Post Description</th>
										<th>Action</th>
									</tr>
								</thead>
								<tbody>
									@foreach ($posts as $post)
										<tr>
											<td>{{$post->id}}</td>
											<td>{{$post->title}}</td>
											<td>{{$post->body}}</td>
											<td>
												<a href="/posts/{{$post->id}}" class="btn btn-info">Details</a>
												<a href="edit-post/{{$post->id}}" class="btn btn-success">Edit</a>
												<a href="/delete-post/{{$post->id}}" class="btn btn-danger">Delete</a>
											</td>
										</tr>
									@endforeach
								</tbody>
							</table>
						</div>
					</div>
					
				</div>
			</div>
		</div>
	</section>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js" integrity="sha384-ygbV9kiqUc6oa4msXn9868pTtWMgiQaeYH7/t7LECLbyPA2x65Kgf80OJFdroafW" crossorigin="anonymous"></script>
</body>
</html>