<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
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
                'name' => 'required|string',
                'brand' => 'required|string',
                'category_id' => 'required|int',
                'price' => 'required|int',
                'count_in_stock' => 'required|int',
                'description' => 'nullable|string|max:255',
                'image' => 'required|array|min:1',
                'image.*' => 'required|image'
            ];
    }
}
