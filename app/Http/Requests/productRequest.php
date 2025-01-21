<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class productRequest extends FormRequest
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
            'category_id' => 'required|exists:categories,id',
            'name_ar' => 'required|string|unique:products,name_ar',
            'name_en' => 'required|string|unique:products,name_en',
            'image' => 'required|image|mimes:jpg,jpeg,png',
            'barcode' => 'required',
            'desc_ar' => 'required|string',
            'desc_en' => 'required|string',
            'price_discount' => 'required|numeric',
            'colors' => 'required',
            'sizes' => 'required',
            'stock' => 'required|integer',
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success' => false,
            'message' => 'Validation errors',
            'errors' => $validator->errors(),
        ], 422));
    }
}
