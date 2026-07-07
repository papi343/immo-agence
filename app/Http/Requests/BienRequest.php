<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BienRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'titre' => 'required|string|max:255',
            'description' => 'required|string',
            'type' => 'required|string|in:maison,appartement,terrain,local_commercial',
            'statut' => 'required|string|in:a_vendre,a_louer,vendu,loue',
            'prix' => 'required|numeric|min:0',
            'surface' => 'required|numeric|min:0',
            'pieces' => 'nullable|integer|min:0',
            'chambres' => 'nullable|integer|min:0',
            'salles_de_bain' => 'nullable|integer|min:0',
            'adresse' => 'required|string|max:255',
            'ville' => 'required|string|max:255',
            'code_postal' => 'required|string|max:10',
            'features' => 'nullable|array',
            'features.*' => 'string',
            'agent_id' => 'required|exists:users,id',
            'proprietaire_id' => 'nullable|exists:users,id',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,webp|max:4096',
            'primary_image_index' => 'nullable|integer|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'titre.required' => 'Le titre est obligatoire.',
            'description.required' => 'La description est obligatoire.',
            'type.in' => 'Le type doit être parmi : maison, appartement, terrain, local_commercial.',
            'statut.in' => 'Le statut doit être parmi : a_vendre, a_louer, vendu, loue.',
            'prix.required' => 'Le prix est obligatoire.',
            'prix.numeric' => 'Le prix doit être un nombre.',
            'surface.required' => 'La surface est obligatoire.',
            'agent_id.required' => 'L\'agent immobilier est obligatoire.',
            'agent_id.exists' => 'L\'agent spécifié n\'existe pas.',
            'proprietaire_id.exists' => 'Le propriétaire spécifié n\'existe pas.',
            'images.*.image' => 'Chaque fichier doit être une image.',
            'images.*.mimes' => 'Les formats d\'image autorisés sont : jpeg, png, jpg, webp.',
            'images.*.max' => 'Chaque image ne doit pas dépasser 4 Mo.',
        ];
    }
}
