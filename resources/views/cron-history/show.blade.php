<!DOCTYPE html>
<html>
<head>

    <title>Cron Execution Details</title>

    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css"
    >

</head>

<body class="p-4">

<div class="container">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h2>Cron Execution Details</h2>
            <p class="text-muted mb-0">
                Detailed information about this Cron Job execution.
            </p>
        </div>

        <a
            href="{{ route('cron-history.index') }}"
            class="btn btn-secondary"
        >
            Back to History
        </a>

    </div>


    @if($cronJobLog->status === 'failed')

        <div class="alert alert-danger">

            <h5>
                ⚠ Cron Job Failed
            </h5>

            <p class="mb-0">
                This execution was unsuccessful.
            </p>

        </div>

    @else

        <div class="alert alert-success">

            <h5>
                ✓ Cron Job Completed Successfully
            </h5>

            <p class="mb-0">
                This execution completed successfully.
            </p>

        </div>

    @endif


    <div class="card shadow-sm">

        <div class="card-header">

            <strong>
                Execution #{{ $cronJobLog->id }}
            </strong>

        </div>

        <div class="card-body">

            <table class="table table-bordered">

                <tr>

                    <th width="250">
                        Job Name
                    </th>

                    <td>
                        {{ $cronJobLog->job_name }}
                    </td>

                </tr>


                <tr>

                    <th>
                        Status
                    </th>

                    <td>

                        @if($cronJobLog->status === 'success')

                            <span class="badge bg-success">
                                Success
                            </span>

                        @else

                            <span class="badge bg-danger">
                                Failed
                            </span>

                        @endif

                    </td>

                </tr>


                <tr>

                    <th>
                        Records Found
                    </th>

                    <td>
                        {{ $cronJobLog->records_found }}
                    </td>

                </tr>


                <tr>

                    <th>
                        Records Deleted
                    </th>

                    <td>
                        {{ $cronJobLog->records_deleted }}
                    </td>

                </tr>


                <tr>

                    <th>
                        Started At
                    </th>

                    <td>

                        @if($cronJobLog->started_at)

                            {{ $cronJobLog->started_at->format('Y-m-d H:i:s') }}

                        @else

                            -

                        @endif

                    </td>

                </tr>


                <tr>

                    <th>
                        Completed At
                    </th>

                    <td>

                        @if($cronJobLog->completed_at)

                            {{ $cronJobLog->completed_at->format('Y-m-d H:i:s') }}

                        @else

                            -

                        @endif

                    </td>

                </tr>


                <tr>

                    <th>
                        Execution Duration
                    </th>

                    <td>

                        @if($cronJobLog->duration_ms !== null)

                            {{ $cronJobLog->duration_ms }} ms

                        @else

                            -

                        @endif

                    </td>

                </tr>


                @if($cronJobLog->error_message)

                    <tr>

                        <th>
                            Error Message
                        </th>

                        <td>

                            <div class="alert alert-danger mb-0">

                                {{ $cronJobLog->error_message }}

                            </div>

                        </td>

                    </tr>

                @endif

            </table>

        </div>

    </div>

</div>

</body>
</html>