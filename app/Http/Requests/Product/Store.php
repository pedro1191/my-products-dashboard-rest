<?php

namespace App\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class Store extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
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
                'required',
                'min:1',
                'max:100',
                Rule::unique('products')
                    ->where(function ($query) {
                        return $query->where('name', $this->input('name'))
                            ->where('category_id', $this->input('category_id'));
                    }),
            ],
            'description' => ['required', 'min:1', 'max:1000'],
            'category_id' => ['required', 'exists:categories,id'],
            'image' => ['required', 'image', 'max:128'],
        ];
    }
}
