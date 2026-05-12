<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProductIndexRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'filter' => $this->input('filter'),
            'page' => $this->input('page', '1'),
            'sort_by' => $this->input('sort_by', 'created_at'),
            'sort_dir' => $this->input('sort_dir', 'desc'),
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'page' => ['integer', 'min:1'],

            'filter' => ['nullable', 'array'],

            'filter.category_id' => ['nullable', 'integer'],
            'filter.name' => ['string'],
            'filter.price_min' => ['numeric', 'min:0'],
            'filter.price_max' => ['numeric', 'min:0'],

            'sort_by' => ['in:price,created_at'],
            'sort_dir' => ['in:asc,desc'],
        ];
    }
}
