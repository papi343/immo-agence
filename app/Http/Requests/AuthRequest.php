<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class AuthRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'tel' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:8',
            'role' => 'required|string|max:255|in:admin,agent,client',
            
        ];
    }

    public function messages(): array
    {
        return [
            'nom.required' => 'Le nom est requis',
            'prenom.required' => 'Le prenom est requis',
            'tel.required' => 'Le telephone est requis',
            'email.required' => 'L\'email est requis',
            'email.unique' => 'Cet email existe deja',
            'password.required' => 'Le mot de passe est requis',
            'role.required' => 'Le role est requis',
            'role.in' => 'Le role doit etre admin, agent ou client',
            
        ];
    }
}
