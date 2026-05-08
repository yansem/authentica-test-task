<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductIndexRequest;
use App\Http\Requests\ProductStoreRequest;
use App\Http\Requests\ProductUpdateRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Queries\ProductQuery;
use App\Services\ProductCacheService;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;
use Illuminate\Pagination\LengthAwarePaginator;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(ProductIndexRequest $request, ProductQuery $query, ProductCacheService $cache): AnonymousResourceCollection
    {
        $data = $request->validated();

        $page = (int) $request->input('page', 1);
        $perPage = 15;

        $items = $cache->remember($data, function () use ($query, $data) {
            return $query->index($data)
                ->get()
                ->toArray();
        });

        $collection = collect($items);

        $paginated = new LengthAwarePaginator(
            $collection->forPage($page, $perPage)->values(),
            $collection->count(),
            $perPage,
            $page,
            [
                'path' => request()->url(),
                'query' => request()->query(),
            ]
        );

        return ProductResource::collection($paginated);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductStoreRequest $request): ProductResource
    {
        $validated = $request->validated();

        return new ProductResource(Product::create($validated));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProductUpdateRequest $request, Product $product): ProductResource
    {
        $validated = $request->validated();

        $product->update($validated);

        return new ProductResource($product);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product): Response
    {
        $product->delete();

        return response()->noContent();
    }
}
