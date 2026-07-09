<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Visite;
use App\Http\Requests\VisiteRequest;
use App\Http\Resources\VisiteResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VisiteController extends Controller
{
    /**
     * Display a listing of visits (filtered by user role).
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = Visite::with(['bien', 'client', 'agent']);

        if ($user->role === 'client') {
            $query->where('client_id', $user->id);
        } elseif ($user->role === 'agent') {
            $query->where('agent_id', $user->id);
        }
        // L'admin peut tout voir.

        $visites = $query->latest()->get();

        return VisiteResource::collection($visites);
    }

    /**
     * Store a newly created visit.
     */
    public function store(VisiteRequest $request)
    {
        $data = $request->validated();
        
        $visite = Visite::create($data);
        $visite->load(['bien', 'client', 'agent']);

        return response()->json([
            'message' => 'Visite planifiée avec succès',
            'data' => new VisiteResource($visite)
        ], 201);
    }

    /**
     * Display the specified visit.
     */
    public function show(Visite $visite)
    {
        $user = Auth::user();
        
        // Sécurité : Vérifier si l'utilisateur a accès à cette visite
        if ($user->role === 'client' && $visite->client_id !== $user->id) {
            return response()->json(['message' => 'Non autorisé.'], 403);
        }
        if ($user->role === 'agent' && $visite->agent_id !== $user->id) {
            return response()->json(['message' => 'Non autorisé.'], 403);
        }

        $visite->load(['bien', 'client', 'agent']);
        return new VisiteResource($visite);
    }

    /**
     * Update the specified visit.
     */
    public function update(Request $request, Visite $visite)
    {
        $user = Auth::user();

        // Sécurité
        if ($user->role === 'client' && $visite->client_id !== $user->id) {
            return response()->json(['message' => 'Non autorisé.'], 403);
        }
        if ($user->role === 'agent' && $visite->agent_id !== $user->id) {
            return response()->json(['message' => 'Non autorisé.'], 403);
        }

        $data = $request->validate([
            'date_visite' => 'sometimes|required|date|after:now',
            'statut' => 'sometimes|required|string|in:planifie,effectue,annule',
            'commentaire' => 'nullable|string',
        ]);

        $visite->update($data);
        $visite->load(['bien', 'client', 'agent']);

        return response()->json([
            'message' => 'Visite mise à jour avec succès',
            'data' => new VisiteResource($visite)
        ], 200);
    }

    /**
     * Remove the specified visit.
     */
    public function destroy(Visite $visite)
    {
        $user = Auth::user();
        if ($user->role === 'client' && $visite->client_id !== $user->id) {
            return response()->json(['message' => 'Non autorisé.'], 403);
        }

        $visite->delete();

        return response()->json([
            'message' => 'Visite annulée/supprimée avec succès'
        ], 200);
    }
}
