<?php

namespace App\Services;

use App\Builders\ProductBuilder;
use Illuminate\Support\Facades\Cache;

class ProductService
{
    protected ProductBuilder $productQuery;

    public function __construct(ProductBuilder $productQuery)
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
