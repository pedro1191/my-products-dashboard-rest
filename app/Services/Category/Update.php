<?php

namespace App\Services\Category;

use App\Helpers\Base64;
use App\Models\Category;

class Update
{
    public function execute(Category $category, array $data): Category
    {
        if (isset($data['image'])) {
            $base64Image = Base64::generateBase64String($data['image']);
            $data['image'] = $base64Image;
        }

        $category->update($data);

        return $category;
    }
}
