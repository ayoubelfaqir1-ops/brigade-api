<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Plat;
use App\Http\Requests\StorePlatRequest;
use App\Http\Requests\UpdatePlatRequest;
use Symfony\Component\HttpFoundation\Request;

class PlatController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = $request->user()->plats()->with('category');
        
        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }
        
        if ($request->has('category_id')) {
            $query->where('category_id', $request->category_id);
        }
        
        if ($request->has('available')) {
            $query->where('is_available', $request->boolean('available'));
        }
        
        $plats = $query->paginate($request->get('per_page', 15));
        return response()->json($plats, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePlatRequest $request)
    {
        $plat = $request->user()->plats()->create($request->validated());
        return response()->json($plat->load('category'), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Plat $plat)
    {
        $this->authorize('view', $plat);
        return response()->json($plat->load('category'), 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePlatRequest $request, Plat $plat)
    {
        $plat->update($request->validated());
        return response()->json($plat->load('category'), 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Plat $plat)
    {
        $this->authorize('delete', $plat);
        
        $plat->delete();

        return response()->json([
            'message' => 'Plat deleted successfully'
        ], 200);
    }
}