<?php

namespace App\Http\Requests\API\Product;

use Illuminate\Foundation\Http\FormRequest;

class IndexRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'page' => 'nullable|string',
            'category_id' => 'nullable|string',
            'tag_id' => 'nullable|string',
            'search' => 'nullable|string',
            'sort' => 'nullable|string',
            'category_filter_id' => 'nullable|string',
        ];
    }
}
