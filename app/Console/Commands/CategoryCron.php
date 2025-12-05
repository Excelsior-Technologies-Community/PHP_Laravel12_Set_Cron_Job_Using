<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Category;

class CategoryCron extends Command
{
    protected $signature = 'category:cron';
    protected $description = 'Cron job for category table actions';

    public function handle()
{
    // Delete ALL categories
    Category::truncate();

    \Log::info("Category Cron: All categories deleted at " . now());

    return Command::SUCCESS;
}

    }