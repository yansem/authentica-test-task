<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;

class ProductCacheService
{
    private const TTL = 300;
    private const TAG = 'products';

    public function remember(array $filters, callable $callback)
    {
        return Cache::tags([self::TAG])->remember(
                $this->makeKey($filters),
                self::TTL,
                $callback
            );
    }

    private function makeKey(array $filters): string
    {
        ksort($filters);

        return 'products:index:' . md5(json_encode($filters));
    }
}
