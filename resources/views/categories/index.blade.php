<!DOCTYPE html>
<html>
<head>
    <title>Category List</title>

    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
    >
</head>

<body class="p-4">

    <h2>Category List</h2>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <a
        href="{{ route('categories.create') }}"
        class="btn btn-primary mb-3"
    >
        Add Category
    </a>

    {{-- Search / Filter Form --}}
    <form
        method="GET"
        action="{{ route('categories.index') }}"
        class="row g-3 mb-3"
    >
        <div class="col-md-4">
            <input
                type="text"
                name="search"
                class="form-control"
                placeholder="Search by name..."
                value="{{ request('search') }}"
            >
        </div>

        <div class="col-md-2">
            <input
                type="date"
                name="from_date"
                class="form-control"
                value="{{ request('from_date') }}"
            >
        </div>

        <div class="col-md-2">
            <input
                type="date"
                name="to_date"
                class="form-control"
                value="{{ request('to_date') }}"
            >
        </div>

        <div class="col-md-2">
            <button
                type="submit"
                class="btn btn-secondary w-100"
            >
                Filter
            </button>
        </div>

        <div class="col-md-2">
            <a
                href="{{ route('categories.index') }}"
                class="btn btn-outline-secondary w-100"
            >
                Reset
            </a>
        </div>
    </form>


    {{-- Bulk Delete Form --}}
    <form
        method="POST"
        action="{{ route('categories.bulk-destroy') }}"
        id="bulkDeleteForm"
        onsubmit="return validateBulkDelete();"
    >
        @csrf

        <div class="d-flex justify-content-between align-items-center mb-2">
            <button
                type="submit"
                class="btn btn-danger btn-sm"
            >
                Bulk Delete
            </button>
        </div>


        <table class="table table-bordered">

            <thead>
                <tr>

                    <th width="40">
                        <input
                            type="checkbox"
                            id="selectAll"
                            onclick="toggleAll(this)"
                        >
                    </th>

                    <th width="80">

                        <a href="{{ route('categories.index', array_merge(request()->query(), [
                            'sort' => 'id',
                            'direction' =>
                                request('direction') === 'asc' &&
                                request('sort') === 'id'
                                    ? 'desc'
                                    : 'asc'
                        ])) }}">

                            ID

                            @if(request('sort') === 'id')
                                <span class="text-{{
                                    request('direction') === 'asc'
                                        ? 'primary'
                                        : 'secondary'
                                }}">
                                    &#8593;&#8595;
                                </span>
                            @endif

                        </a>

                    </th>


                    <th>

                        <a href="{{ route('categories.index', array_merge(request()->query(), [
                            'sort' => 'name',
                            'direction' =>
                                request('direction') === 'asc' &&
                                request('sort') === 'name'
                                    ? 'desc'
                                    : 'asc'
                        ])) }}">

                            Name

                            @if(request('sort') === 'name')
                                <span class="text-{{
                                    request('direction') === 'asc'
                                        ? 'primary'
                                        : 'secondary'
                                }}">
                                    &#8593;&#8595;
                                </span>
                            @endif

                        </a>

                    </th>


                    <th width="120">

                        <a href="{{ route('categories.index', array_merge(request()->query(), [
                            'sort' => 'created_at',
                            'direction' =>
                                request('direction') === 'asc' &&
                                request('sort') === 'created_at'
                                    ? 'desc'
                                    : 'asc'
                        ])) }}">

                            Created At

                            @if(request('sort') === 'created_at')
                                <span class="text-{{
                                    request('direction') === 'asc'
                                        ? 'primary'
                                        : 'secondary'
                                }}">
                                    &#8593;&#8595;
                                </span>
                            @endif

                        </a>

                    </th>


                    <th width="180">
                        Actions
                    </th>

                </tr>
            </thead>


            <tbody>

                @forelse($categories as $category)

                    <tr>

                        {{-- Bulk Checkbox --}}
                        <td>
                            <input
                                type="checkbox"
                                name="category_ids[]"
                                value="{{ $category->id }}"
                                class="bulk-checkbox"
                            >
                        </td>


                        {{-- ID --}}
                        <td>
                            {{ $category->id }}
                        </td>


                        {{-- Name --}}
                        <td>
                            {{ $category->name }}
                        </td>


                        {{-- Created At --}}
                        <td>
                            {{ $category->created_at->format('Y-m-d H:i') }}
                        </td>


                        {{-- Actions --}}
                        <td>

                            <a
                                href="{{ route('categories.show', $category) }}"
                                class="btn btn-info btn-sm"
                            >
                                View
                            </a>


                            <a
                                href="{{ route('categories.edit', $category) }}"
                                class="btn btn-warning btn-sm"
                            >
                                Edit
                            </a>


                            {{-- Individual Delete --}}
                            <button
                                type="button"
                                class="btn btn-danger btn-sm"
                                onclick="deleteCategory({{ $category->id }})"
                            >
                                Delete
                            </button>

                        </td>

                    </tr>

                @empty

                    <tr>
                        <td
                            colspan="5"
                            class="text-center"
                        >
                            No categories found
                        </td>
                    </tr>

                @endforelse

            </tbody>

        </table>

    </form>


    {{-- Individual Delete Forms --}}
    @foreach($categories as $category)

        <form
            id="delete-form-{{ $category->id }}"
            action="{{ route('categories.destroy', $category) }}"
            method="POST"
            style="display: none;"
        >
            @csrf
            @method('DELETE')
        </form>

    @endforeach


    {{-- Pagination --}}
    <div class="mt-3">
        {{ $categories->links() }}
    </div>


    <script>

        /*
        |--------------------------------------------------------------------------
        | Select / Deselect All
        |--------------------------------------------------------------------------
        */

        function toggleAll(source) {

            document
                .querySelectorAll('.bulk-checkbox')
                .forEach(function (checkbox) {

                    checkbox.checked = source.checked;

                });

        }


        /*
        |--------------------------------------------------------------------------
        | Individual Category Delete
        |--------------------------------------------------------------------------
        */

        function deleteCategory(id) {

            if (
                confirm(
                    'Are you sure you want to delete this category?'
                )
            ) {

                document
                    .getElementById('delete-form-' + id)
                    .submit();

            }

        }


        /*
        |--------------------------------------------------------------------------
        | Bulk Delete Validation
        |--------------------------------------------------------------------------
        */

        function validateBulkDelete() {

            const selectedCategories =
                document.querySelectorAll(
                    '.bulk-checkbox:checked'
                );

            if (selectedCategories.length === 0) {

                alert(
                    'Please select at least one category to delete.'
                );

                return false;

            }

            return confirm(
                'Are you sure you want to delete the selected categories?'
            );

        }

    </script>

</body>
</html>