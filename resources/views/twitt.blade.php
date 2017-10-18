<!DOCTYPE html>
<html>
<head>
    <title>Laravel 5 - Twitter API</title>
    <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
</head>
<body>

<div class="container">
    <h2>Laravel 5 - Twitter API</h2>

    <form method="POST" action="{{ route('post.tweet') }}" enctype="multipart/form-data">

        {{ csrf_field() }}

        @if(count($errors))
            <div class="alert alert-danger">
                <strong>Whoops!</strong> There were some problems with your input.
                <br/>
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="form-group">
            <label>Add Tweet Text:</label>
            <textarea class="form-control" name="message"></textarea>
        </div>
        <div class="form-group">
            <label>Add Multiple Images:</label>
            <input type="file" name="images[]" multiple class="form-control">
        </div>
        <div class="form-group">
            <button class="btn btn-success">Add New Tweet</button>
        </div>
    </form>

    
    </table>
</div>

</body>
</html>