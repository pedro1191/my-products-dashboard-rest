<?php

namespace App\Services\Category;

use App\Models\Category;

class Store
{
    public function execute(array $data): Category
    {
        $category = new Category($data);
        $category->save();

        return $category;
    }
}
