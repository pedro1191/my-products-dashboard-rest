<?php

namespace App\Services\Product;

use App\Models\Product;

class Index
{
    public function execute(array $data)
    {
        return Product::query()
            ->when($data['name'] ?? null, function ($query, $name) {
                $query->whereRaw('LOWER(name) LIKE ?')
                    ->setBindings([$name]);
            })
            ->when($data['description'] ?? null, function ($query, $description) {
                $query->whereRaw('LOWER(description) LIKE ?')
                    ->setBindings([$description]);
            })
            ->when($data['category_id'] ?? null, function ($query, $categoryId) {
                $query->where('category_id', $categoryId);
            })
            ->orderBy($data['orderBy'], $data['orderDirection'])
            ->paginate();
    }
}
