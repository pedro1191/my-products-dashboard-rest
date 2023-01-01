<?php

namespace App\Services\Product;

use App\Helpers\Base64;
use App\Models\Product;

class Store
{
    public function execute(array $data): Product
    {
        $base64Image = Base64::generateBase64String($data['image']);
        $data['image'] = $base64Image;

        $product = new Product($data);
        $product->save();

        return $product;
    }
}
