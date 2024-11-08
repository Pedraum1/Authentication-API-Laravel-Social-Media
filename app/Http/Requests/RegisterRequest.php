<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
            'nameInput'                  => 'required|min:6|max:30',
            'emailInput'                 => 'required|email|unique:users,email',
            'tagInput'                   => 'required|max:10|regex:/^[a-zA-Z0-9]+$/|unique:users,tag',
            'passwordInput'              => 'required|min:5|max:24|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/',
            'passwordInput_confirmation' => 'required|same:passwordInput'
        ];
    }

    public function messages():array
    {
        return [
            'nameInput.required' => 'O nome de usuário é obrigatório',
            'nameInput.min'      => 'O nome deve ter no mínimo 6 caracteres',
            'nameInput.max'      => 'O nome deve ter no máximo 30 caracteres',
  
            'emailInput.required' => 'O Email é obrigatório',
            'emailInput.email'    => 'O Email deve ser válido',
            'emailInput.unique'   => 'O Email já foi cadastrado',
  
            'tagInput.required' => 'A Tag de usuário é obrigatória',
            'tagInput.max'      => 'A Tag deve ter no máximo 10 caracteres',
            'tagInput.regex'    => 'A Tag não pode ter caracteres especiais',
            'tagInput.unique'   => 'A Tag já foi cadastrada',
  
            'passwordInput.required' => 'A Senha é obrigatório',
            'passwordInput.min'      => 'A Senha deve ter no mínimo 5 caracteres',
            'passwordInput.max'      => 'A Senha deve ter no máximo 24 caracteres',
            'passwordInput.regex'    => 'A Senha deve ter pelo menos 1 letra maiúscula, 1 letra minúscula e 1 digito',
  
            'passwordInput_confirmation.required' => 'A Senha precisa ser confirmada',
            'passwordInput_confirmation.same'     => 'As senhas inseridas não batem'
        ];
    }
}
