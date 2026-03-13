<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use Symfony\Component\HttpFoundation\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $categories = $request->user()->categories()->with('plats')->get();
            return response()->json($categories, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch categories'], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategoryRequest $request)
    {
        try {
            $category = $request->user()->categories()->create($request->validated());
            return response()->json($category, 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to create category'], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        try {
            $this->authorize('view',$category);
            return response()->json($category->load('plats'), 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Category not found'], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoryRequest $request, Category $category)
    {
        try {
            $category->update($request->validated());
            return response()->json($category, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to update category'], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
        try {
            $this->authorize('delete',$category);
            $category->delete();
            return response()->json(['message' => 'Category deleted successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to delete category'], 500);
        }
    }

    /**
     * Get all plats for a specific category.
     */
    public function plats(Category $category)
    {
        try {
            $this->authorize('view', $category);
            $plats = $category->plats;
            return response()->json($plats, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch category plats'], 500);
        }
    }

    /**
     * Get category statistics.
     */
    public function stats(Category $category)
    {
        try {
            $this->authorize('view', $category);
            
            $stats = [
                'plats_count' => $category->plats()->count(),
                'average_price' => $category->plats()->avg('price') ?? 0,
                'min_price' => $category->plats()->min('price') ?? 0,
                'max_price' => $category->plats()->max('price') ?? 0,
            ];
            
            return response()->json($stats, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch category stats'], 500);
        }
    }
}