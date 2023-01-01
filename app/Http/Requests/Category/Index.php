<?php

namespace App\Http\Requests\Category;

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
            'orderBy' => ['required', 'in:id,name'],
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

        $this->merge([
            'orderBy' => $this->orderBy ?? 'name',
            'orderDirection' => $this->orderDirection ?? OrderDirection::Ascending->value,
        ]);
    }
}
