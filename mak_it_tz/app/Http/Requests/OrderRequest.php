<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Symfony\Component\HttpFoundation\Response;

class OrderRequest extends FormRequest
{
    public function rules()
    {
        $rules = [
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'total_price' => 'required|numeric|min:0',
        ];

         if ($this->isMethod('put')) {
            $rules = array_merge($rules, [
                'product_id' => 'sometimes|required|exists:products,id',
                'quantity' => 'sometimes|required|min:1',
                'total_price' => 'sometimes|required|numeric|min:0',
            ]);
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'product_id.required' => 'The product ID is required.',
            'product_id.exists' => 'The selected product does not exist.',
            'quantity.required' => 'The quantity is required.',
            'quantity.integer' => 'The quantity must be a whole number.',
            'quantity.min' => 'The quantity must be at least :min.',
            'total_price.required' => 'The total price is required.',
            'total_price.numeric' => 'The total price must be a number.',
            'total_price.min' => 'The total price must be at least :min.',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        $errorResponse = response()->json([
            'message' => 'Validation error',
            'errors'  => $validator->errors(),
        ], Response::HTTP_UNPROCESSABLE_ENTITY);

        throw new HttpResponseException($errorResponse);
    }
}
