<!DOCTYPE html>
<html>

<head>

    <title>Category Management</title>

    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">

    <style>
        body {
            background: #f5f7fb;
        }

        .stat-card {
            border: none;
            border-radius: 14px;
            transition: 0.2s;
        }

        .stat-card:hover {
            transform: translateY(-2px);
        }

        .stat-number {
            font-size: 28px;
            font-weight: 700;
        }

        .table {
            vertical-align: middle;
        }

        .status-badge {
            min-width: 80px;
        }

        .action-buttons {
            white-space: nowrap;
        }

        .pagination {
            margin-bottom: 0;
        }
    </style>

</head>


<body class="p-4">


    <div class="container-fluid">


        {{-- Header --}}

        <div class="d-flex justify-content-between align-items-center mb-4">

            <div>

                <h2 class="fw-bold mb-1">
                    Category Management
                </h2>

                <p class="text-muted mb-0">
                    Manage categories, status and deleted records
                </p>

            </div>


            <div class="d-flex gap-2">

                <a
                    href="{{ route('cron-history.index') }}"
                    class="btn btn-dark">
                    Cron History
                </a>

                <a
                    href="{{ route('categories.create') }}"
                    class="btn btn-primary">
                    + Add Category
                </a>

            </div>

        </div>


        {{-- Success Message --}}

        @if(session('success'))

        <div class="alert alert-success alert-dismissible fade show">

            {{ session('success') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"></button>

        </div>

        @endif


        {{-- Error Message --}}

        @if(session('error'))

        <div class="alert alert-danger alert-dismissible fade show">

            {{ session('error') }}

            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"></button>

        </div>

        @endif


        {{-- =========================================================
         STATISTICS
    ========================================================== --}}

        <div class="row g-3 mb-4">


            <div class="col-md-3">

                <div class="card stat-card shadow-sm p-3">

                    <div class="text-muted">
                        Total Categories
                    </div>

                    <div class="stat-number">
                        {{ $totalCategories }}
                    </div>

                </div>

            </div>


            <div class="col-md-3">

                <div class="card stat-card shadow-sm p-3">

                    <div class="text-muted">
                        Active
                    </div>

                    <div class="stat-number text-success">
                        {{ $activeCategories }}
                    </div>

                </div>

            </div>


            <div class="col-md-3">

                <div class="card stat-card shadow-sm p-3">

                    <div class="text-muted">
                        Inactive
                    </div>

                    <div class="stat-number text-warning">
                        {{ $inactiveCategories }}
                    </div>

                </div>

            </div>


            <div class="col-md-3">

                <div class="card stat-card shadow-sm p-3">

                    <div class="text-muted">
                        Trash
                    </div>

                    <div class="stat-number text-danger">
                        {{ $trashedCategories }}
                    </div>

                </div>

            </div>


        </div>


        {{-- =========================================================
         VIEW BUTTONS
    ========================================================== --}}

        <div class="mb-3">

            @if(request('view') === 'trash')

            <a
                href="{{ route('categories.index') }}"
                class="btn btn-outline-primary">
                ← Back to Categories
            </a>

            @else

            <a
                href="{{ route('categories.index', ['view' => 'trash']) }}"
                class="btn btn-outline-danger">
                🗑 View Trash ({{ $trashedCategories }})
            </a>

            @endif

        </div>


        {{-- =========================================================
         SEARCH / FILTER
    ========================================================== --}}

        <div class="card shadow-sm mb-4">

            <div class="card-body">

                <form
                    method="GET"
                    action="{{ route('categories.index') }}"
                    class="row g-3">


                    @if(request('view') === 'trash')

                    <input
                        type="hidden"
                        name="view"
                        value="trash">

                    @endif


                    {{-- Search --}}

                    <div class="col-md-3">

                        <label class="form-label">
                            Search
                        </label>

                        <input
                            type="text"
                            name="search"
                            class="form-control"
                            placeholder="Search category..."
                            value="{{ request('search') }}">

                    </div>


                    {{-- Status --}}

                    @if(request('view') !== 'trash')

                    <div class="col-md-2">

                        <label class="form-label">
                            Status
                        </label>

                        <select
                            name="status"
                            class="form-select">

                            <option value="">
                                All Status
                            </option>

                            <option
                                value="active"
                                {{ request('status') === 'active' ? 'selected' : '' }}>
                                Active
                            </option>

                            <option
                                value="inactive"
                                {{ request('status') === 'inactive' ? 'selected' : '' }}>
                                Inactive
                            </option>

                        </select>

                    </div>

                    @endif


                    {{-- From Date --}}

                    <div class="col-md-2">

                        <label class="form-label">
                            From Date
                        </label>

                        <input
                            type="date"
                            name="from_date"
                            class="form-control"
                            value="{{ request('from_date') }}">

                    </div>


                    {{-- To Date --}}

                    <div class="col-md-2">

                        <label class="form-label">
                            To Date
                        </label>

                        <input
                            type="date"
                            name="to_date"
                            class="form-control"
                            value="{{ request('to_date') }}">

                    </div>


                    <div class="col-md-1 d-flex align-items-end">

                        <button
                            type="submit"
                            class="btn btn-primary w-100">
                            Filter
                        </button>

                    </div>


                    <div class="col-md-2 d-flex align-items-end">

                        <a
                            href="{{ request('view') === 'trash'
                            ? route('categories.index', ['view' => 'trash'])
                            : route('categories.index') }}"
                            class="btn btn-outline-secondary w-100">
                            Reset
                        </a>

                    </div>


                </form>

            </div>

        </div>


        {{-- =========================================================
         ACTION BAR
    ========================================================== --}}

        @if(request('view') !== 'trash')

        <div class="d-flex justify-content-between mb-3">

            <form
                method="POST"
                action="{{ route('categories.bulk-destroy') }}"
                id="bulkDeleteForm"
                onsubmit="return validateBulkDelete();">

                @csrf


                <button
                    type="submit"
                    class="btn btn-danger">
                    🗑 Bulk Delete
                </button>

            </form>


            {{-- CSV Export --}}

            <a
                href="{{ route('categories.export', request()->query()) }}"
                class="btn btn-success">
                📥 Export CSV
            </a>

        </div>

        @endif


        {{-- =========================================================
         TABLE
    ========================================================== --}}

        <div class="card shadow-sm">

            <div class="card-body p-0">

                <div class="table-responsive">

                    <table class="table table-bordered table-hover mb-0">


                        <thead class="table-light">

                            <tr>


                                @if(request('view') !== 'trash')

                                <th width="45">

                                    <input
                                        type="checkbox"
                                        id="selectAll"
                                        onclick="toggleAll(this)">

                                </th>

                                @endif


                                <th width="80">

                                    <a
                                        href="{{ route('categories.index', array_merge(
                                        request()->query(),
                                        [
                                            'sort' => 'id',
                                            'direction' =>
                                                request('direction') === 'asc' &&
                                                request('sort') === 'id'
                                                    ? 'desc'
                                                    : 'asc'
                                        ]
                                    )) }}">
                                        ID
                                    </a>

                                </th>


                                <th>

                                    <a
                                        href="{{ route('categories.index', array_merge(
                                        request()->query(),
                                        [
                                            'sort' => 'name',
                                            'direction' =>
                                                request('direction') === 'asc' &&
                                                request('sort') === 'name'
                                                    ? 'desc'
                                                    : 'asc'
                                        ]
                                    )) }}">
                                        Name
                                    </a>

                                </th>


                                @if(request('view') !== 'trash')

                                <th width="130">

                                    Status

                                </th>

                                @endif


                                <th width="180">

                                    Created At

                                </th>


                                <th width="250">

                                    Actions

                                </th>


                            </tr>

                        </thead>


                        <tbody>


                            @forelse($categories as $category)


                            <tr>


                                @if(request('view') !== 'trash')

                                <td>

                                    <input
                                        type="checkbox"
                                        name="category_ids[]"
                                        value="{{ $category->id }}"
                                        class="bulk-checkbox"
                                        form="bulkDeleteForm">

                                </td>

                                @endif


                                <td>

                                    {{ $category->id }}

                                </td>


                                <td>

                                    <strong>
                                        {{ $category->name }}
                                    </strong>

                                </td>


                                @if(request('view') !== 'trash')

                                <td>

                                    @if($category->status === 'active')

                                    <span
                                        class="badge bg-success status-badge">
                                        Active
                                    </span>

                                    @else

                                    <span
                                        class="badge bg-warning text-dark status-badge">
                                        Inactive
                                    </span>

                                    @endif

                                </td>

                                @endif


                                <td>

                                    {{ $category->created_at
                                        ? $category->created_at->format('Y-m-d H:i')
                                        : '-' }}

                                </td>


                                <td class="action-buttons">


                                    @if(request('view') === 'trash')


                                    {{-- Restore --}}

                                    <form
                                        method="POST"
                                        action="{{ route('categories.restore', $category->id) }}"
                                        class="d-inline">

                                        @csrf

                                        @method('PATCH')

                                        <button
                                            type="submit"
                                            class="btn btn-success btn-sm">
                                            ♻ Restore
                                        </button>

                                    </form>


                                    {{-- Permanent Delete --}}

                                    <form
                                        method="POST"
                                        action="{{ route('categories.force-delete', $category->id) }}"
                                        class="d-inline"
                                        onsubmit="return confirmPermanentDelete();">

                                        @csrf

                                        @method('DELETE')

                                        <button
                                            type="submit"
                                            class="btn btn-danger btn-sm">
                                            Delete Forever
                                        </button>

                                    </form>


                                    @else


                                    {{-- View --}}

                                    <a
                                        href="{{ route('categories.show', $category) }}"
                                        class="btn btn-info btn-sm">
                                        View
                                    </a>


                                    {{-- Edit --}}

                                    <a
                                        href="{{ route('categories.edit', $category) }}"
                                        class="btn btn-warning btn-sm">
                                        Edit
                                    </a>


                                    {{-- Toggle Status --}}

                                    <form
                                        method="POST"
                                        action="{{ route('categories.toggle-status', $category) }}"
                                        class="d-inline">

                                        @csrf

                                        @method('PATCH')

                                        <button
                                            type="submit"
                                            class="btn btn-secondary btn-sm">

                                            {{ $category->status === 'active'
                                                    ? 'Deactivate'
                                                    : 'Activate' }}

                                        </button>

                                    </form>


                                    {{-- Delete --}}

                                    <button
                                        type="button"
                                        class="btn btn-danger btn-sm"
                                        onclick="deleteCategory({{ $category->id }})">
                                        Delete
                                    </button>


                                    @endif


                                </td>


                            </tr>


                            @empty


                            <tr>

                                <td
                                    colspan="7"
                                    class="text-center py-4">

                                    No categories found.

                                </td>

                            </tr>


                            @endforelse


                        </tbody>


                    </table>

                </div>

            </div>

        </div>


        {{-- =========================================================
         INDIVIDUAL DELETE FORMS
    ========================================================== --}}

        @if(request('view') !== 'trash')

        @foreach($categories as $category)

        <form
            id="delete-form-{{ $category->id }}"
            action="{{ route('categories.destroy', $category) }}"
            method="POST"
            style="display:none;">

            @csrf

            @method('DELETE')

        </form>

        @endforeach

        @endif


        {{-- =========================================================
         PAGINATION
    ========================================================== --}}

        <div class="mt-3 d-flex justify-content-center">

            {{ $categories->onEachSide(1)->links() }}

        </div>


    </div>


    <script>
        /*
    |--------------------------------------------------------------------------
    | Select All
    |--------------------------------------------------------------------------
    */

        function toggleAll(source) {
            document
                .querySelectorAll('.bulk-checkbox')
                .forEach(function(checkbox) {

                    checkbox.checked = source.checked;

                });
        }


        /*
        |--------------------------------------------------------------------------
        | Delete Category
        |--------------------------------------------------------------------------
        */

        function deleteCategory(id) {
            if (
                confirm(
                    'Are you sure you want to move this category to trash?'
                )
            ) {

                document
                    .getElementById(
                        'delete-form-' + id
                    )
                    .submit();

            }
        }


        /*
        |--------------------------------------------------------------------------
        | Bulk Delete
        |--------------------------------------------------------------------------
        */

        function validateBulkDelete() {
            const selected =
                document.querySelectorAll(
                    '.bulk-checkbox:checked'
                );

            if (selected.length === 0) {

                alert(
                    'Please select at least one category.'
                );

                return false;
            }

            return confirm(
                'Are you sure you want to move selected categories to trash?'
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Permanent Delete
        |--------------------------------------------------------------------------
        */

        function confirmPermanentDelete() {
            return confirm(
                'WARNING: This category will be permanently deleted and cannot be restored. Continue?'
            );
        }
    </script>


    <script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>


</body>

</html>