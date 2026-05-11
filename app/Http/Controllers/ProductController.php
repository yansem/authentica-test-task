<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductIndexRequest;
use App\Http\Requests\ProductStoreRequest;
use App\Http\Requests\ProductUpdateRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Services\ProductService;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(ProductIndexRequest $request, ProductService $productService): AnonymousResourceCollection
    {
        $validated = $request->validated();

        $products = $productService->index($validated);

        return ProductResource::collection($products);
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
