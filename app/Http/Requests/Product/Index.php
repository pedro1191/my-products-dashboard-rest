<?php

namespace App\Http\Requests\Product;

use App\Enums\OrderDirection;
use App\Helpers\StringHelper;
use Illuminate\Foundation\Http\FormRequest;

class Index extends FormRequest
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
            'name' => ['sometimes', 'required'],
            'description' => ['sometimes', 'required'],
            'category_id' => ['sometimes', 'required', 'exists:categories,id'],
            'orderBy' => ['required', 'in:id,name,description,category_id'],
            'orderDirection' => ['required', 'in:' . OrderDirection::csv()],
        ];
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        if (isset($this->name) && is_string($this->name)) {
            $this->merge([
                'name' => StringHelper::prepareForDBSearch($this->name),
            ]);
        }

        if (isset($this->description) && is_string($this->description)) {
            $this->merge([
                'description' => StringHelper::prepareForDBSearch($this->description),
            ]);
        }

        $this->merge([
            'orderBy' => $this->orderBy ?? 'name',
            'orderDirection' => $this->orderDirection ?? OrderDirection::Ascending->value,
        ]);
    }
}
