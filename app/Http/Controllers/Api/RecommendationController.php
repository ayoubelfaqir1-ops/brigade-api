<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRecommendationRequest;
use App\Models\Plat;
use App\Models\Recommendation;
use App\Services\RecommendationService;
use Illuminate\Http\Request;

class RecommendationController extends Controller
{
    public function __construct(
        private RecommendationService $service
        //       │
        //       └── Laravel auto injects the service
    ) {}
    
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $recommendations = Recommendation::where('user_id', auth()->user()->id)
            ->with('plat')
            ->latest()
            ->get();

        return response()->json($recommendations);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRecommendationRequest $request)
    {
        $user = $request->user();
        $plat = Plat::find($request->plat_id);

        if (!$plat) {
            return response()->json(['error' => 'Plat not found'], 404);
        }

        try {
            // Analyze compatibility using the service
            $result = $this->service->analyze($user, $plat);

            // Create the recommendation with analysis results and ready status
            $recommendation = Recommendation::create([
                'user_id' => $user->id,
                'plat_id' => $plat->id,
                'score' => $result['score'] ?? 0,
                'compatible' => $result['compatible'] ?? false,
                'reasoning' => $result['reasoning'] ?? '',
                'warning_message' => json_encode($result['warnings'] ?? []),
                'status' => Recommendation::STATUS_READY,
            ]);

            return response()->json($recommendation->load('plat'), 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Analysis failed: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
