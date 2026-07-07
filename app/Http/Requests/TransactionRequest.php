<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TransactionRequest extends FormRequest
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
            'type' => 'required|string|in:vente,location',
            'prix_final' => 'required|numeric|min:0',
            'commission' => 'required|numeric|min:0',
            'date_transaction' => 'required|date',
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
            'type.in' => 'Le type doit être vente ou location.',
            'prix_final.required' => 'Le prix final est requis.',
            'commission.required' => 'La commission est requise.',
            'date_transaction.required' => 'La date de la transaction est requise.',
        ];
    }
}
