<?php
namespace App\Transformers;

use League\Fractal;

class CategoryTransformer extends Fractal\TransformerAbstract
{
    public function transform(\App\Category $category)
    {
        return [
            'id' => (int) $category->id,
            'name' => $category->name,
            'link' => app('Dingo\Api\Routing\UrlGenerator')->version('v1')->route('api.categories.show', ['id' => $category->id]),
        ];
    }
}
