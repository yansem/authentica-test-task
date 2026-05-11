<?php

use App\Queries\ProductQuery;
use App\Services\ProductService;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

uses(TestCase::class);

it('caches products index result', function () {
    Cache::flush();

    $validated = [
        'filter' => [
            'name' => 'iphone',
        ],
        'sort_by' => 'id',
        'sort_dir' => 'asc',
    ];

    $paginator = new LengthAwarePaginator(
        [1, 2],
        2,
        15
    );

    $productQuery = Mockery::mock(ProductQuery::class);

    $productQuery
        ->shouldReceive('index')
        ->once()
        ->with($validated)
        ->andReturn($paginator);

    $service = new ProductService($productQuery);

    $result1 = $service->index($validated);
    $result2 = $service->index($validated);

    expect($result1)->toBe($result2);

    expect($result1->items())->toHaveCount(2);
    expect($result2->items())->toHaveCount(2);
});
