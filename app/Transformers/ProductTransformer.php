<?php
namespace App\Transformers;

use League\Fractal;

class ProductTransformer extends Fractal\TransformerAbstract
{
    public function transform(\App\Product $product)
    {
        return [
            'id' => (int) $product->id,
            'name' => $product->name,
            'description' => $product->description,
            'link' => app('Dingo\Api\Routing\UrlGenerator')->version('v1')->route('api.products.show', ['id' => $product->id]),
        ];
    }
}
