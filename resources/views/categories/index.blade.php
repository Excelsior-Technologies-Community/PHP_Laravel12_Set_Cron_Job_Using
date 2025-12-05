<!DOCTYPE html>
<html>
<head>
    <title>Category List</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
</head>
<body class="p-4">

    <h2>Category List</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <a href="{{ route('categories.create') }}" class="btn btn-primary mb-3">Add Category</a>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th width="80">ID</th>
                <th>Name</th>
            </tr>
        </thead>

        <tbody>
            @forelse($categories as $category)
                <tr>
                    <td>{{ $category->id }}</td>
                    <td>{{ $category->name }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="2" class="text-center">No categories found</td>
                </tr>
            @endforelse
        </tbody>
    </table>

</body>
</html>
