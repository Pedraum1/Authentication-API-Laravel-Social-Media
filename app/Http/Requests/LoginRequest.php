<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return True;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'emailInput'    => 'required|email',
            'passwordInput' => 'required',
        ];
    }

    public function messages():array
    {
        return [
            'emailInput.required'    => 'Insira seu Email para realizar login',
            'emailInput.email'       => 'O Email inserido deve ser válido',
            'passwordInput.required' => 'Insira sua senha para realizar login',
        ];
    }
}
