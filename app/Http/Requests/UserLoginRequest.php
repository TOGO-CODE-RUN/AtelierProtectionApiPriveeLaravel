<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserLoginRequest extends FormRequest
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
            'email' => 'required|email',
            'password' => 'required|string|min:8',
        ];
    }

    public function messages()
    {
        return [
            'email.required' => "Veuillez entrer une adresse email.",
            'email.email' => "Veuillez entrer une adresse email valide.",
            'password.required' => "Le mot de passe est obligatoire.",
            'password.min' => "Le mot de passe doit comporter au moins 8 caractères.",
        ];
    }
}
