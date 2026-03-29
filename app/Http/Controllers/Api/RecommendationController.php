<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRecommendationRequest;
use App\Http\Resources\RecommendationResource;
use App\Jobs\ProcessRecommendation;
use App\Models\Plat;
use App\Models\Recommendation;
use App\Services\RecommendationService;
use Illuminate\Http\Request;

class RecommendationController extends Controller
{
    public function __construct(
        private RecommendationService $service
    ) {}

    /**
     * Display a listing of the resource.
     */
    public function index(Plat $plat = null)
    {
        $recommendations = Recommendation::where('user_id', auth()->user()->id)
            ->with(['plat.category', 'plat.ingredients', 'user'])
            ->when($plat, function ($query) use ($plat) {
                return $query->where('plat_id', $plat->id);
            })
            ->latest()
            ->get();

        return response()->json(RecommendationResource::collection($recommendations), 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRecommendationRequest $request, Plat $plat)
    {
        $user = $request->user();

        if (!$plat) {
            return response()->json(['error' => 'Plat not found'], 404);
        }

        try {
            ProcessRecommendation::dispatch($user, $plat, $this->service);
            return response()->json(['message' => 'Recommendation analysis queued successfully'], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Analysis failed: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Recommendation $recommendation)
    {
        // Ensure user can only see their own recommendations
        if ($recommendation->user_id !== auth()->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $recommendation->load(['plat.category', 'plat.ingredients', 'user']);
        return response()->json(new RecommendationResource($recommendation), 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Recommendation $recommendation)
    {
        // Ensure user can only delete their own recommendations
        if ($recommendation->user_id !== auth()->id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $recommendation->delete();
        return response()->json(['message' => 'Recommendation deleted successfully'], 200);
    }
}
