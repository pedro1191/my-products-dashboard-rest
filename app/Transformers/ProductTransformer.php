<?php
namespace App\Transformers;

use League\Fractal;

class ProductTransformer extends Fractal\TransformerAbstract
{
    protected $availableIncludes = [
        'category',
    ];

    public function transform(\App\Product $product)
    {
        return [
            'id' => (int) $product->id,
            'name' => $product->name,
            'description' => $product->description,
            'image' => $product->image,
            'link' => app('Dingo\Api\Routing\UrlGenerator')->version('v1')->route('api.products.show', ['id' => $product->id]),
        ];
    }

    public function includeCategory(\App\Product $product)
    {
        $category = $product->category;

        return $this->item($category, new CategoryTransformer);
    }
}
