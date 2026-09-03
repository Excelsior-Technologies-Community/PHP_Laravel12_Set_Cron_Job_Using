<?php

namespace App\Http\Controllers;

use App\Models\CronJobLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class CronJobLogController extends Controller
{
    /**
     * Display cron execution history.
     */
    public function index(Request $request)
    {
        $query = CronJobLog::query();

        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {
            $query->where(
                'job_name',
                'like',
                '%' . $request->search . '%'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Status filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('status')) {
            $query->where(
                'status',
                $request->status
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Date filter
        |--------------------------------------------------------------------------
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
        |--------------------------------------------------------------------------
        | Statistics
        |--------------------------------------------------------------------------
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
        |--------------------------------------------------------------------------
        | Latest executions
        |--------------------------------------------------------------------------
        */

        $logs = $query
            ->oldest('started_at')
            ->paginate(5)
            ->appends($request->all());

        /*
        |--------------------------------------------------------------------------
        | Recent failures
        |--------------------------------------------------------------------------
        */

        $recentFailures = CronJobLog::where(
            'status',
            'failed'
        )
            ->oldest('started_at')
            ->limit(5)
            ->get();

        /*
        |--------------------------------------------------------------------------
        | Last execution
        |--------------------------------------------------------------------------
        */

        $lastExecution = CronJobLog::oldest(
            'started_at'
        )->first();

        /*
        |--------------------------------------------------------------------------
        | Last successful execution
        |--------------------------------------------------------------------------
        */

        $lastSuccessfulExecution =
            CronJobLog::where(
                'status',
                'success'
            )
            ->oldest('started_at')
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

    /**
     * Run category cron manually.
     */
    public function runNow()
    {
        $exitCode = Artisan::call(
            'category:cron',
            [
                '--days' => 30,
            ]
        );

        $output = Artisan::output();

        if ($exitCode === 0) {
            return redirect()
                ->route('cron-history.index')
                ->with(
                    'success',
                    'Category Cron executed successfully.'
                )
                ->with(
                    'cron_output',
                    $output
                );
        }

        return redirect()
            ->route('cron-history.index')
            ->with(
                'error',
                'Category Cron execution failed.'
            )
            ->with(
                'cron_output',
                $output
            );
    }
}
