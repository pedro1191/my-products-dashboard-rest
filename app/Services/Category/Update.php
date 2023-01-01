<?php

namespace App\Services\Category;

use App\Models\Category;

class Update
{
    public function execute(Category $category, array $data): Category
    {
        $category->update($data);

        return $category;
    }
}
