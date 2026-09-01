<!DOCTYPE html>
<html>
<head>
    <title>Category Details</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>
<body class="p-4">

    <h2>Category Details</h2>

    <a href="{{ route('categories.index') }}" class="btn btn-secondary mb-3">Back to List</a>

    <table class="table table-bordered">
        <tr>
            <th width="150">ID</th>
            <td>{{ $category->id }}</td>
        </tr>
        <tr>
            <th>Name</th>
            <td>{{ $category->name }}</td>
        </tr>
        <tr>
            <th>Created At</th>
            <td>{{ $category->created_at->format('Y-m-d H:i:s') }}</td>
        </tr>
        <tr>
            <th>Updated At</th>
            <td>{{ $category->updated_at->format('Y-m-d H:i:s') }}</td>
        </tr>
    </table>

    <div class="mt-3">
        <a href="{{ route('categories.edit', $category) }}" class="btn btn-warning">Edit</a>
        <form action="{{ route('categories.destroy', $category) }}" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this category?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger">Delete</button>
        </form>
    </div>

</body>
</html>
