<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Symfony\Component\HttpFoundation\Response;

class ProductRequest extends FormRequest
{
    public function rules(): array
    {
        $rules = [
            'name' => 'required|string',
            'description' => 'required|string',
            'price' => 'required|numeric',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];

        if ($this->isMethod('put')) {
            $rules = array_merge($rules, [
                'name' => 'sometimes|required|string',
                'description' => 'sometimes|required|string',
                'price' => 'sometimes|required|numeric',
            ]);
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Поле "Название" обязательно для заполнения.',
            'description.required' => 'Поле "Описание" обязательно для заполнения.',
            'price.required' => 'Поле "Цена" обязательно для заполнения.',
            'price.numeric' => 'Поле "Цена" должно содержать только числовое значение.',
            'photo.image' => 'Файл фотографии должен быть изображением.',
            'photo.mimes' => 'Формат изображения должен быть jpeg, png, jpg или gif.',
            'photo.max' => 'Размер изображения не должен превышать 2048 КБ.',
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
