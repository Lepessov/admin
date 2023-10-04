<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Symfony\Component\HttpFoundation\Response;

class UserRegisterRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'email'    => ['required', 'string', 'email', 'max:255'],
            'password' => ['required', 'string', 'min:8'],
            'role_id'  => ['required', 'exists:roles,id'], // Ensure "role_id" exists in the "roles" table
        ];
    }

    public function messages(): array
    {
        return [
            'email.string'      => 'Поле email должно быть строкой',
            'email.max'         => 'Поле email не должно превышать 255 символов',
            'email.required'    => 'Поле email обязательно для заполнения',
            'email.email'       => 'Невалидный адрес электронной почты',
            'password.required' => 'Поле password обязательно для заполнения',
            'password.min'      => 'Поле password должно содержать минимум 8 символов',
            'role_id.required'  => 'Поле role_id обязательно для заполнения',
            'role_id.exists'    => 'Выбранный role_id не существует',
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
