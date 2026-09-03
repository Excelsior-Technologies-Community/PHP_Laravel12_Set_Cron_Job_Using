<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('cron_job_logs', function (Blueprint $table) {
            $table->id();

            $table->string('job_name');

            $table->enum('status', [
                'success',
                'failed'
            ])->default('success');

            $table->unsignedInteger('records_found')->default(0);
            $table->unsignedInteger('records_deleted')->default(0);

            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();

            $table->unsignedBigInteger('duration_ms')->nullable();

            $table->text('error_message')->nullable();

            $table->timestamps();

            $table->index('job_name');
            $table->index('status');
            $table->index('started_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cron_job_logs');
    }
};