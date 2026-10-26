<?php

use App\Models\Job;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        Job::where('status', 'pending')->chunkById(100, function ($jobs) {
            foreach ($jobs as $job) {
                // Le modèle renseigne published_at et programme le push après commit.
                $job->update(['status' => 'published']);
            }
        });
    }

    public function down(): void
    {
        // Une publication et ses notifications ne peuvent pas être annulées.
    }
};
