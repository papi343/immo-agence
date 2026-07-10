<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\Bien;
use App\Http\Requests\TransactionRequest;
use App\Http\Resources\TransactionResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    /**
     * Display a listing of transactions.
     */
    public function index()
    {
        $user = Auth::user();
        $query = Transaction::with(['bien', 'client', 'agent']);

        if ($user->role === 'client') {
            $query->where('client_id', $user->id);
        } elseif ($user->role === 'agent') {
            $query->where('agent_id', $user->id);
        }
        // Admin voit toutes les transactions.

        $transactions = $query->latest()->get();

        return TransactionResource::collection($transactions);
    }

    /**
     * Store a newly created transaction.
     */
    public function store(TransactionRequest $request)
    {
        $user = Auth::user();
        
        // Seuls les agents et admins peuvent enregistrer des transactions
        if ($user->role === 'client') {
            return response()->json(['message' => 'Non autorisé. Seuls les agents et administrateurs peuvent conclure des transactions.'], 403);
        }

        $data = $request->validated();
        
        $transaction = Transaction::create($data);

        // Mettre à jour le statut du bien correspondant
        $bien = Bien::find($data['bien_id']);
        if ($bien) {
            $nouveauStatut = ($data['type'] === 'vente') ? 'vendu' : 'loue';
            $bien->update(['statut' => $nouveauStatut]);
        }

        $transaction->load(['bien', 'client', 'agent']);

        return response()->json([
            'message' => 'Transaction enregistrée et statut du bien mis à jour.',
            'data' => new TransactionResource($transaction)
        ], 201);
    }

    /**
     * Display the specified transaction.
     */
    public function show(Transaction $transaction)
    {
        $user = Auth::user();

        // Sécurité
        if ($user->role === 'client' && $transaction->client_id !== $user->id) {
            return response()->json(['message' => 'Non autorisé.'], 403);
        }
        if ($user->role === 'agent' && $transaction->agent_id !== $user->id) {
            return response()->json(['message' => 'Non autorisé.'], 403);
        }

        $transaction->load(['bien', 'client', 'agent']);
        return new TransactionResource($transaction);
    }

    /**
     * Remove the specified transaction (Admin only).
     */
    public function destroy(Transaction $transaction)
    {
        if (Auth::user()->role !== 'admin') {
            return response()->json(['message' => 'Non autorisé. Action réservée aux administrateurs.'], 403);
        }

        // Si on supprime la transaction, on remet éventuellement le bien à l'état précédent
        $bien = $transaction->bien;
        if ($bien) {
            $ancienStatut = ($transaction->type === 'vente') ? 'a_vendre' : 'a_louer';
            $bien->update(['statut' => $ancienStatut]);
        }

        $transaction->delete();

        return response()->json([
            'message' => 'Transaction supprimée et statut du bien réinitialisé.'
        ], 200);
    }
}
