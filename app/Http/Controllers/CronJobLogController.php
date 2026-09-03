<?php

namespace App\Http\Controllers;

use App\Models\CronJobLog;
use Illuminate\Http\Request;

class CronJobLogController extends Controller
{
    /**
     * Display cron execution history.
     */
    public function index(Request $request)
    {
        $query = CronJobLog::query();

        /*
         * Search by job name.
         */
        if ($request->filled('search')) {
            $query->where(
                'job_name',
                'like',
                '%' . $request->search . '%'
            );
        }

        /*
         * Filter by status.
         */
        if ($request->filled('status')) {
            $query->where(
                'status',
                $request->status
            );
        }

        /*
         * Filter by date.
         */
        if ($request->filled('from_date')) {
            $query->whereDate(
                'started_at',
                '>=',
                $request->from_date
            );
        }

        if ($request->filled('to_date')) {
            $query->whereDate(
                'started_at',
                '<=',
                $request->to_date
            );
        }

        /*
         * Statistics.
         */
        $totalRuns = (clone $query)->count();

        $successfulRuns = (clone $query)
            ->where('status', 'success')
            ->count();

        $failedRuns = (clone $query)
            ->where('status', 'failed')
            ->count();

        $totalDeleted = (clone $query)
            ->sum('records_deleted');

        /*
         * Latest executions.
         */
        $logs = $query
            ->latest('started_at')
            ->paginate(10)
            ->appends($request->all());

        /*
         * Recent failures for alert.
         */
        $recentFailures = CronJobLog::where('status', 'failed')
            ->latest('started_at')
            ->limit(5)
            ->get();

        /*
         * Last execution.
         */
        $lastExecution = CronJobLog::latest('started_at')->first();

        /*
         * Last successful execution.
         */
        $lastSuccessfulExecution = CronJobLog::where(
            'status',
            'success'
        )
            ->latest('started_at')
            ->first();

        return view(
            'cron-history.index',
            compact(
                'logs',
                'totalRuns',
                'successfulRuns',
                'failedRuns',
                'totalDeleted',
                'recentFailures',
                'lastExecution',
                'lastSuccessfulExecution'
            )
        );
    }

    /**
     * Display one cron execution.
     */
    public function show(CronJobLog $cronJobLog)
    {
        return view(
            'cron-history.show',
            compact('cronJobLog')
        );
    }
}