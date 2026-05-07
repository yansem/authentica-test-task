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
                isset($filter['category_id']),
                fn ($q) => $q->where('category_id', $filter['category_id'])
            )
            ->when(
                isset($filter['name']),
                function ($q) use ($filter) {
                    $name = trim($filter['name']);

                    if ($name !== '') {
                        $q->where('name', 'like', '%' . $name . '%');
                    }
                }
            )
            ->when(
                isset($filter['price_min']),
                fn ($q) => $q->where('price', '>=', $filter['price_min'])
            )
            ->when(
                isset($filter['price_max']),
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
