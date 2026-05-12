<?php

namespace App\Builders;

use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;


class ProductBuilder
{
    public function index(array $data): LengthAwarePaginator
    {
        $query = Product::query();

        return $this->applyIndexFilters($query, $data['filter'])
            ->orderBy($data['sort_by'], $data['sort_dir'])
            ->paginate(15);
    }

    private function applyIndexFilters(Builder $query, ?array $filter): Builder
    {
        if (is_null($filter)) return $query;

        return $query
            ->when(
                array_key_exists('category_id', $filter),
                fn ($q) => $q->where('category_id', $filter['category_id'])
            )
            ->when(
                array_key_exists('name', $filter),
                function ($q) use ($filter) {
                    $name = trim($filter['name']);

                    if ($name !== '') {
                        $q->where('name', 'like', '%' . $name . '%');
                    }
                }
            )
            ->when(
                array_key_exists('price_min', $filter),
                fn ($q) => $q->where('price', '>=', $filter['price_min'])
            )
            ->when(
                array_key_exists('price_max', $filter),
                fn ($q) => $q->where('price', '<=', $filter['price_max'])
            );
    }
}
