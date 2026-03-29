<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Plat;
use App\Models\Category;
use App\Models\Ingredient;
use App\Models\Recommendation;
use Illuminate\Http\JsonResponse;

class AdminController extends Controller
{
    public function dashboard(): JsonResponse
    {
        $this->authorize('viewAdminDashboard', auth()->user());
        
        $totalPlates = Plat::count();
        $totalCategories = Category::count();
        $totalIngredients = Ingredient::count();
        $totalRecommendations = Recommendation::count();

        // Most recommended plate (highest average score)
        $mostRecommended = Recommendation::selectRaw('plat_id, AVG(score) as avg_score')
            ->groupBy('plat_id')
            ->orderByDesc('avg_score')
            ->with('plat')
            ->first();

        // Least recommended plate (lowest average score)
        $leastRecommended = Recommendation::selectRaw('plat_id, AVG(score) as avg_score')
            ->groupBy('plat_id')
            ->orderBy('avg_score')
            ->with('plat')
            ->first();

        // Category with most plates
        $topCategory = Category::withCount('plats')
            ->orderByDesc('plats_count')
            ->first();

        return response()->json([
            'total_plates' => $totalPlates,
            'total_categories' => $totalCategories,
            'total_ingredients' => $totalIngredients,
            'total_recommendations' => $totalRecommendations,
            'most_recommended_plate' => $mostRecommended ? [
                'plate' => $mostRecommended->plat,
                'average_score' => round($mostRecommended->avg_score, 2)
            ] : null,
            'least_recommended_plate' => $leastRecommended ? [
                'plate' => $leastRecommended->plat,
                'average_score' => round($leastRecommended->avg_score, 2)
            ] : null,
            'top_category' => $topCategory ? [
                'category' => $topCategory,
                'plates_count' => $topCategory->plats_count
            ] : null
        ]);
    }
}