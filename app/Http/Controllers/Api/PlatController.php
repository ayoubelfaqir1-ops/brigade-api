<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Plat;
use App\Http\Requests\StorePlatRequest;
use App\Http\Requests\UpdatePlatRequest;
use App\Http\Requests\UploadImageRequest;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Request;

class PlatController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Plat::with('category','ingredients');

        if ($request->has('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->has('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->has('available')) {
            $query->where('is_available', $request->boolean('available'));
        }

        // gest used to exxtract the param per_page from the url the default value if that param not founded is 15
        $plats = $query->paginate($request->get('per_page', 15));
        return response()->json($plats, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePlatRequest $request)
    {
        $plat = Plat::create($request->validated());
        if($request->filled('ingredients'))
        {
            $plat->ingredients()->attach($request->ingredients);
        }
        return response()->json($plat->load('category'), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Plat $plat)
    {
        return response()->json($plat->load('category','ingredients'), 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePlatRequest $request, Plat $plat)
    {
        $plat->update($request->validated());
        if($request->has('ingredients'))
        {
            $plat->ingredients()->sync($request->ingredients);
        }
        return response()->json($plat->fresh()->load('category','ingredients'));
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

    public function updateImage(UploadImageRequest $request, Plat $plat)
    {

        if ($plat->getRawOriginal('image')) {
            Storage::disk('public')->delete($plat->getRawOriginal('image'));
        }
        $imagePath = $request->file('image')->store('plats', 'public');
        $plat->update([
            'image' => $imagePath,
        ]);

        return response()->json($plat);
    }

    public function ingredients(Plat $plat)
    {
        return response()->json($plat->load('ingredients'));
    }
}
