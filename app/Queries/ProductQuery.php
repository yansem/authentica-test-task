<?php

namespace App\Queries;

use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;


class ProductQuery
{
    public function index(array $data): Builder
    {
        $query = Product::query();

        $filter = $data['filter'] ?? [];

        $query = $this->applyFilters($query, $filter);

        return $this->applySorting($query, $data);
    }

    private function applyFilters(Builder $query, array $filter): Builder
    {
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

    private function applySorting(Builder $query, array $data): Builder
    {
        $sortBy = $data['sort_by'] ?? 'created_at';
        $sortDir = $data['sort_dir'] ?? 'desc';

        return $query->orderBy($sortBy, $sortDir);
    }
}
