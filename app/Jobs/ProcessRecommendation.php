<?php

namespace App\Jobs;

use App\Models\Plat;
use App\Models\Recommendation;
use App\Models\User;
use App\Services\RecommendationService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class ProcessRecommendation implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        private User           $user,
        private Plat           $plat,
        private RecommendationService $service
    )
    {
        
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Analyze compatibility using the service
            $result = $this->service->analyze($this->user, $this->plat);

            // Create the recommendation with analysis results and ready status
            $recommendation = Recommendation::create([
                'user_id' => $this->user->id,
                'plat_id' => $this->plat->id,
                'score' => $result['score'] ?? 0,
                'compatible' => $result['compatible'] ?? false,
                'reasoning' => $result['reasoning'] ?? '',
                'warning_message' => json_encode($result['warnings'] ?? []),
                'status' => Recommendation::STATUS_READY,
            ]);
    }
}
