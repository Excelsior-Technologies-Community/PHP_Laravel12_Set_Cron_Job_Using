<?php

namespace App\Console\Commands;

use App\Models\Category;
use App\Models\CronJobLog;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class CategoryCron extends Command
{
    protected $signature = 'category:cron
                            {--dry-run : Run in preview mode without deleting any records}
                            {--days=30 : Delete categories older than this many days}';

    protected $description = 'Cron job for cleaning up old categories';

    public function handle(): int
    {
        $startedAt = now();
        $startTime = microtime(true);

        $isDryRun = $this->option('dry-run');
        $days = (int) $this->option('days');

        $cronLog = CronJobLog::create([
            'job_name' => 'category:cron',
            'status' => 'success',
            'records_found' => 0,
            'records_deleted' => 0,
            'started_at' => $startedAt,
        ]);

        try {
            if ($days < 1) {
                throw new \InvalidArgumentException(
                    'The --days option must be greater than 0.'
                );
            }

            $cutoffDate = Carbon::now()->subDays($days);

            $categoriesToDelete = Category::where(
                'created_at',
                '<',
                $cutoffDate
            )
                ->where(
                    'status',
                    'inactive'
                )
                ->get();

            $recordsFound = $categoriesToDelete->count();

            $cronLog->update([
                'records_found' => $recordsFound,
            ]);

            if ($recordsFound === 0) {
                $this->info(
                    "No categories older than {$days} days found."
                );

                Log::info(
                    "Category Cron: No categories to delete. Cutoff: {$cutoffDate}"
                );

                $this->completeCronLog(
                    $cronLog,
                    $startTime,
                    0
                );

                return Command::SUCCESS;
            }

            $this->info(
                "Found {$recordsFound} categories older than {$days} days."
            );

            /*
             * Dry-run mode
             */
            if ($isDryRun) {
                foreach ($categoriesToDelete as $category) {
                    $this->line(
                        "Would delete: [ID: {$category->id}] {$category->name} " .
                            "(created: {$category->created_at})"
                    );
                }

                $this->warn(
                    'Dry-run mode: No records were deleted.'
                );

                Log::info(
                    "Category Cron: Dry-run completed. " .
                        "{$recordsFound} records would be deleted."
                );

                $this->completeCronLog(
                    $cronLog,
                    $startTime,
                    0
                );

                return Command::SUCCESS;
            }

            /*
             * Delete old categories inside a transaction.
             */
            $deletedCount = 0;

            DB::transaction(function () use (
                $categoriesToDelete,
                &$deletedCount
            ) {
                foreach ($categoriesToDelete as $category) {
                    $category->delete();

                    $deletedCount++;

                    $this->line(
                        "Deleted: [ID: {$category->id}] {$category->name}"
                    );
                }
            });

            $this->completeCronLog(
                $cronLog,
                $startTime,
                $deletedCount
            );

            Log::info(
                "Category Cron: Deleted {$deletedCount} categories " .
                    "older than {$days} days at " . now()
            );

            $this->info(
                "Successfully deleted {$deletedCount} categories."
            );

            return Command::SUCCESS;
        } catch (Throwable $exception) {

            $durationMs = (int) round(
                (microtime(true) - $startTime) * 1000
            );

            $cronLog->update([
                'status' => 'failed',
                'completed_at' => now(),
                'duration_ms' => $durationMs,
                'error_message' => $exception->getMessage(),
            ]);

            Log::error(
                'Category Cron failed.',
                [
                    'error' => $exception->getMessage(),
                    'duration_ms' => $durationMs,
                ]
            );

            $this->error(
                'Category Cron failed: ' .
                    $exception->getMessage()
            );

            return Command::FAILURE;
        }
    }

    /**
     * Mark cron execution as successfully completed.
     */
    private function completeCronLog(
        CronJobLog $cronLog,
        float $startTime,
        int $deletedCount
    ): void {
        $durationMs = (int) round(
            (microtime(true) - $startTime) * 1000
        );

        $cronLog->update([
            'status' => 'success',
            'records_deleted' => $deletedCount,
            'completed_at' => now(),
            'duration_ms' => $durationMs,
        ]);
    }
}
