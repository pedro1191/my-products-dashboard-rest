<?php

namespace App\Services\Category;

use App\Models\Category;

class Index
{
    public function execute(array $data)
    {
        return Category::query()
            ->when($data['name'] ?? null, function ($query, $name) {
                $query->whereRaw('LOWER(name) LIKE ?')
                    ->setBindings([$name]);
            })
            ->orderBy($data['orderBy'], $data['orderDirection'])
            ->paginate();
    }
}
