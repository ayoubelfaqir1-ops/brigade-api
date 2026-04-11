<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreRecommendationRequest;
use App\Http\Resources\RecommendationResource;
use App\Jobs\ProcessRecommendation;
use App\Models\Plat;
use App\Models\Recommendation;
use Illuminate\Http\Request;

class RecommendationController extends Controller
{
    public function __construct() {
        // Authorization handled in individual methods
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Plat|null $plat = null)
    {
        $this->authorize('viewAny', Recommendation::class);
        
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
        $this->authorize('analyze', Recommendation::class);
        
        $user = $request->user();

        if (!$plat) {
            return response()->json(['error' => 'Plat not found'], 404);
        }

        try {
            ProcessRecommendation::dispatch($user, $plat);
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
        $this->authorize('view', $recommendation);
        
        $recommendation->load(['plat.category', 'plat.ingredients', 'user']);
        return response()->json(new RecommendationResource($recommendation), 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Recommendation $recommendation)
    {
        $this->authorize('delete', $recommendation);
        
        $recommendation->delete();
        return response()->json(['message' => 'Recommendation deleted successfully'], 200);
    }
}
