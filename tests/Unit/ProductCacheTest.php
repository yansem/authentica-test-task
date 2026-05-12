<?php

use App\Builders\ProductBuilder;
use App\Services\ProductService;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

uses(TestCase::class);

it('caches products index result', function () {
    Cache::flush();

    $productQuery = Mockery::mock(ProductBuilder::class);

    $productQuery
        ->shouldReceive('index')
        ->once();

    $service = new ProductService($productQuery);

    $service->index([]);
    $service->index([]);
});
