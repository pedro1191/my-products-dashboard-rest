<?php

namespace App\Http\Requests\Product;

use App\Models\Product;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class Update extends FormRequest
{
    private Product $product;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $this->product = $this->route('product');

        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name' => [
                'sometimes',
                'required',
                'min:1',
                'max:100',
                Rule::unique('products')
                    ->where(function ($query) {
                        return $query->where('id', '!=', $this->product->id)
                            ->where('name', $this->input('name'))
                            ->where('category_id', $this->input('category_id') ?? $this->product->category->id);
                    }),
            ],
            'description' => ['sometimes', 'required', 'min:1', 'max:1000'],
            'category_id' => ['sometimes', 'required', 'exists:categories,id'],
            'image' => ['sometimes', 'required', 'image', 'max:128'],
        ];
    }
}
