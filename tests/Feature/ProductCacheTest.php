<?php

use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

uses(RefreshDatabase::class);

it('caches products list and invalidates cache after product creation', function () {
    $category = Category::factory()->create();

    Product::factory()->create([
        'name' => 'iPhone 15',
        'price' => 1200,
        'category_id' => $category->id,
    ]);

    $cacheKey = 'products:index:' . md5(json_encode([]));

    DB::flushQueryLog();
    DB::enableQueryLog();

    Cache::flush();

    $this->getJson('/api/products')
        ->assertOk()
        ->assertJsonFragment([
            'name' => 'iPhone 15',
        ]);

    expect(count(DB::getQueryLog()))->toBe(1);

    DB::flushQueryLog();

    expect(Cache::tags('products')->has($cacheKey))->toBeTrue();

    $this->getJson('/api/products')->assertOk();

    expect(count(DB::getQueryLog()))->toBe(0);

    Product::create([
        'name' => 'Samsung Galaxy',
        'price' => 900,
        'category_id' => $category->id,
    ]);

    DB::flushQueryLog();

    expect(Cache::tags('products')->has($cacheKey))->toBeFalse();

    $this->getJson('/api/products')
        ->assertOk()
        ->assertJsonFragment([
            'name' => 'Samsung Galaxy',
        ]);

    expect(count(DB::getQueryLog()))->toBe(1);

    expect(Cache::tags('products')->has($cacheKey))->toBeTrue();
});
