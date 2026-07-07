<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class VisiteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'bien_id' => 'required|exists:biens,id',
            'client_id' => 'required|exists:users,id',
            'agent_id' => 'required|exists:users,id',
            'date_visite' => 'required|date|after:now',
            'statut' => 'nullable|string|in:planifie,effectue,annule',
            'commentaire' => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'bien_id.required' => 'Le bien immobilier est requis.',
            'bien_id.exists' => 'Le bien immobilier spécifié n\'existe pas.',
            'client_id.required' => 'Le client est requis.',
            'client_id.exists' => 'Le client spécifié n\'existe pas.',
            'agent_id.required' => 'L\'agent immobilier est requis.',
            'agent_id.exists' => 'L\'agent spécifié n\'existe pas.',
            'date_visite.required' => 'La date et l\'heure de la visite sont requises.',
            'date_visite.after' => 'La date de la visite doit être dans le futur.',
            'statut.in' => 'Le statut doit être planifie, effectue ou annule.',
        ];
    }
}
