<?php

namespace App\Http\Requests;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;


class UserRegisterRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    // Méthode qui détermine si l'utilisateur est autorisé à faire cette requête
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    // Définit les règles de validation qui s'appliquent à la requête
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:60',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8',
        ];
    }


    // Cette méthode est appelée lorsque la validation échoue
    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success' => false,
            'status' => 422,
            // 'message' => "L'opération a échoué ! Veuillez réessayer.",
            'message' => $validator->errors(),
        ], 422));
    }

    public function messages()
    {
        return [
            'name.required' => "Veuillez entrer votre nom et prenom.",
            'email.required' => "Veuillez entrer une adresse email valide.",
            'email.unique' => "Compte existant, veillez vous connectez!",
            'email.email' => "L'adresse email est invalide.",
            'password.required' => 'Le mot de passe est obligatoire.',
            'password.min' => 'Le mot de passe doit contenir au moins 8 caractères.',
        ];
    }
}
