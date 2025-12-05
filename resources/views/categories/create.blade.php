<!DOCTYPE html>
<html>
<head>
    <title>Add Category</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>
<body class="p-4">

    <h2>Add Category</h2>

    <a href="{{ route('categories.index') }}" class="btn btn-secondary mb-3">Back</a>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('categories.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label class="form-label">Category Name</label>
            <input type="text" name="name" class="form-control" placeholder="Enter category name">
        </div>

        <button type="submit" class="btn btn-success">Save</button>
    </form>

</body>
</html>
