<!DOCTYPE html>
<html>
<head>
    <title>Cron Job Monitoring</title>

    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
    >

    <style>
        body {
            background: #f8f9fa;
        }

        .stat-card {
            border: none;
            border-radius: 12px;
        }

        .stat-number {
            font-size: 28px;
            font-weight: 700;
        }

        .status-success {
            color: #198754;
            font-weight: 600;
        }

        .status-failed {
            color: #dc3545;
            font-weight: 600;
        }

        .alert-card {
            border-left: 5px solid #dc3545;
        }
    </style>
</head>

<body class="p-4">

<div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h2 class="mb-1">Cron Job Monitoring</h2>
            <p class="text-muted mb-0">
                Monitor scheduled Laravel Cron Job executions
            </p>
        </div>

        <div>
            <a
                href="{{ route('categories.index') }}"
                class="btn btn-secondary"
            >
                Categories
            </a>
        </div>

    </div>

    {{-- Failure Alert --}}

    @if($recentFailures->count() > 0)

        <div class="alert alert-danger alert-card">

            <h5 class="alert-heading">
                ⚠ Cron Job Failure Alert
            </h5>

            <p>
                One or more Cron Job executions have failed.
            </p>

            <ul class="mb-0">

                @foreach($recentFailures as $failure)

                    <li>
                        <strong>{{ $failure->job_name }}</strong>

                        failed at

                        {{ optional($failure->started_at)->format('Y-m-d H:i:s') }}

                        -

                        {{ $failure->error_message }}
                    </li>

                @endforeach

            </ul>

        </div>

    @endif


    {{-- Statistics --}}

    <div class="row g-3 mb-4">

        <div class="col-md-3">

            <div class="card stat-card shadow-sm p-3">

                <div class="text-muted">
                    Total Executions
                </div>

                <div class="stat-number">
                    {{ $totalRuns }}
                </div>

            </div>

        </div>


        <div class="col-md-3">

            <div class="card stat-card shadow-sm p-3">

                <div class="text-muted">
                    Successful
                </div>

                <div class="stat-number text-success">
                    {{ $successfulRuns }}
                </div>

            </div>

        </div>


        <div class="col-md-3">

            <div class="card stat-card shadow-sm p-3">

                <div class="text-muted">
                    Failed
                </div>

                <div class="stat-number text-danger">
                    {{ $failedRuns }}
                </div>

            </div>

        </div>


        <div class="col-md-3">

            <div class="card stat-card shadow-sm p-3">

                <div class="text-muted">
                    Categories Deleted
                </div>

                <div class="stat-number">
                    {{ $totalDeleted }}
                </div>

            </div>

        </div>

    </div>


    {{-- Execution Summary --}}

    <div class="card shadow-sm mb-4">

        <div class="card-body">

            <div class="row">

                <div class="col-md-6">

                    <strong>Last Execution</strong>

                    <p class="mb-0 text-muted">

                        @if($lastExecution)

                            {{ $lastExecution->started_at->format('Y-m-d H:i:s') }}

                            -

                            <span
                                class="{{ $lastExecution->isSuccessful()
                                    ? 'status-success'
                                    : 'status-failed' }}"
                            >
                                {{ ucfirst($lastExecution->status) }}
                            </span>

                        @else

                            No executions yet.

                        @endif

                    </p>

                </div>


                <div class="col-md-6">

                    <strong>Last Successful Execution</strong>

                    <p class="mb-0 text-muted">

                        @if($lastSuccessfulExecution)

                            {{ $lastSuccessfulExecution->started_at->format('Y-m-d H:i:s') }}

                        @else

                            No successful execution yet.

                        @endif

                    </p>

                </div>

            </div>

        </div>

    </div>


    {{-- Filters --}}

    <div class="card shadow-sm mb-4">

        <div class="card-body">

            <form
                method="GET"
                action="{{ route('cron-history.index') }}"
                class="row g-3"
            >

                <div class="col-md-3">

                    <input
                        type="text"
                        name="search"
                        class="form-control"
                        placeholder="Search job..."
                        value="{{ request('search') }}"
                    >

                </div>


                <div class="col-md-2">

                    <select
                        name="status"
                        class="form-select"
                    >

                        <option value="">
                            All Status
                        </option>

                        <option
                            value="success"
                            {{ request('status') === 'success' ? 'selected' : '' }}
                        >
                            Success
                        </option>

                        <option
                            value="failed"
                            {{ request('status') === 'failed' ? 'selected' : '' }}
                        >
                            Failed
                        </option>

                    </select>

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


                <div class="col-md-1">

                    <button
                        type="submit"
                        class="btn btn-primary w-100"
                    >
                        Filter
                    </button>

                </div>


                <div class="col-md-2">

                    <a
                        href="{{ route('cron-history.index') }}"
                        class="btn btn-outline-secondary w-100"
                    >
                        Reset
                    </a>

                </div>

            </form>

        </div>

    </div>


    {{-- History Table --}}

    <div class="card shadow-sm">

        <div class="card-header">

            <h5 class="mb-0">
                Cron Execution History
            </h5>

        </div>

        <div class="card-body p-0">

            <div class="table-responsive">

                <table class="table table-bordered table-hover mb-0">

                    <thead class="table-light">

                        <tr>

                            <th>ID</th>

                            <th>Job Name</th>

                            <th>Status</th>

                            <th>Found</th>

                            <th>Deleted</th>

                            <th>Started At</th>

                            <th>Completed At</th>

                            <th>Duration</th>

                            <th>Action</th>

                        </tr>

                    </thead>


                    <tbody>

                        @forelse($logs as $log)

                            <tr>

                                <td>
                                    {{ $log->id }}
                                </td>

                                <td>
                                    <strong>
                                        {{ $log->job_name }}
                                    </strong>
                                </td>

                                <td>

                                    @if($log->status === 'success')

                                        <span class="badge bg-success">
                                            Success
                                        </span>

                                    @else

                                        <span class="badge bg-danger">
                                            Failed
                                        </span>

                                    @endif

                                </td>

                                <td>
                                    {{ $log->records_found }}
                                </td>

                                <td>
                                    {{ $log->records_deleted }}
                                </td>

                                <td>

                                    @if($log->started_at)

                                        {{ $log->started_at->format('Y-m-d H:i:s') }}

                                    @else

                                        -

                                    @endif

                                </td>

                                <td>

                                    @if($log->completed_at)

                                        {{ $log->completed_at->format('Y-m-d H:i:s') }}

                                    @else

                                        -

                                    @endif

                                </td>

                                <td>

                                    @if($log->duration_ms !== null)

                                        {{ $log->duration_ms }} ms

                                    @else

                                        -

                                    @endif

                                </td>

                                <td>

                                    <a
                                        href="{{ route('cron-history.show', $log) }}"
                                        class="btn btn-sm btn-info"
                                    >
                                        View
                                    </a>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td
                                    colspan="9"
                                    class="text-center py-4"
                                >
                                    No Cron Job execution history found.
                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>


    <div class="mt-3">

        {{ $logs->links() }}

    </div>

</div>

</body>
</html>