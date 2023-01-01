<?php

namespace App\Services\Product;

use App\Helpers\Base64;
use App\Models\Product;

class Update
{
    public function execute(Product $product, array $data): Product
    {
        if (isset($data['image'])) {
            $base64Image = Base64::generateBase64String($data['image']);
            $data['image'] = $base64Image;
        }

        $product->update($data);

        return $product;
    }
}
