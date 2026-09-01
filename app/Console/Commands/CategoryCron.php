<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Category;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class CategoryCron extends Command
{
    protected $signature = 'category:cron 
                            {--dry-run : Run in preview mode without deleting any records}
                            {--days=30 : Delete categories older than this many days}';
    protected $description = 'Cron job for cleaning up old categories';

    public function handle()
    {
        $isDryRun = $this->option('dry-run');
        $days = (int) $this->option('days');
        $cutoffDate = Carbon::now()->subDays($days);

        $categoriesToDelete = Category::where('created_at', '<', $cutoffDate)->get();

        if ($categoriesToDelete->isEmpty()) {
            $this->info("No categories older than {$days} days found.");
            Log::info("Category Cron: No categories to delete. Cutoff: {$cutoffDate}");
            return Command::SUCCESS;
        }

        $this->info("Found {$categoriesToDelete->count()} categories older than {$days} days.");

        if ($isDryRun) {
            foreach ($categoriesToDelete as $category) {
                $this->line("Would delete: [ID: {$category->id}] {$category->name} (created: {$category->created_at})");
            }
            $this->warn("Dry-run mode: No records were deleted.");
            Log::info("Category Cron: Dry-run completed. {$categoriesToDelete->count()} records would be deleted.");
            return Command::SUCCESS;
        }

        foreach ($categoriesToDelete as $category) {
            $category->delete();
            $this->line("Deleted: [ID: {$category->id}] {$category->name}");
        }

        Log::info("Category Cron: Deleted {$categoriesToDelete->count()} categories older than {$days} days at " . now());

        $this->info("Successfully deleted {$categoriesToDelete->count()} categories.");
        return Command::SUCCESS;
    }
}
