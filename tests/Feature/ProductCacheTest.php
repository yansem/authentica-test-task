<?php

use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;

uses(RefreshDatabase::class);

it('caches products list and invalidates cache after product creation', function () {
    Cache::flush();

    $category = Category::factory()->create();

    Product::factory()->create([
        'name' => 'iPhone 15',
        'price' => 1200,
        'category_id' => $category->id,
    ]);

    $cacheKey = 'products:index:' . md5(json_encode([]));

    $this->getJson('/api/products')
        ->assertOk()
        ->assertJsonFragment([
            'name' => 'iPhone 15',
        ]);

    expect(Cache::tags('products')->has($cacheKey))->toBeTrue();

    Product::create([
        'name' => 'Samsung Galaxy',
        'price' => 900,
        'category_id' => $category->id,
    ]);

    expect(Cache::tags('products')->has($cacheKey))->toBeFalse();

    $this->getJson('/api/products')
        ->assertOk()
        ->assertJsonFragment([
            'name' => 'Samsung Galaxy',
        ]);

    expect(Cache::tags('products')->has($cacheKey))->toBeTrue();
});
