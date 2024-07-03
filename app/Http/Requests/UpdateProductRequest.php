<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required',
            'category_id' => 'required|exists:product_categories,id',
            'barcode' => 'required',
            'brand' => 'nullable',
            'image' => 'nullable',
            'price' => 'required|numeric',
            'currency' => 'required|in:1,2,3,4',
            'description' => 'nullable',
        ];
    }
}
