<?php

namespace App\Services;

use App\Queries\ProductQuery;
use Illuminate\Support\Facades\Cache;

class ProductService
{
    protected ProductQuery $productQuery;

    public function __construct(ProductQuery $productQuery)
    {
        $this->productQuery = $productQuery;
    }

    public function index(array $validated)
    {
        return Cache::tags(['products'])->remember(
            $this->makeIndexCacheKey($validated),
            300,
            function () use ($validated) {
                return $this->productQuery->index($validated);
            }
        );
    }

    private function makeIndexCacheKey(array $validated): string
    {
        ksort($validated);

        return 'products:index:' . md5(json_encode($validated));
    }
}
