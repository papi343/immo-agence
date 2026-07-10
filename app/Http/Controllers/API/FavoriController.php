<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Favori;
use App\Models\Bien;
use App\Http\Resources\BienResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoriController extends Controller
{
    /**
     * Display the client's favorite properties.
     */
    public function index()
    {
        $user = Auth::user();
        
        // Récupérer les biens favoris
        $biens = Bien::whereHas('favoris', function ($query) use ($user) {
            $query->where('client_id', $user->id);
        })->with(['agent', 'images', 'primaryImage'])->latest()->get();

        return BienResource::collection($biens);
    }

    /**
     * Toggle a property in the client's favorites.
     */
    public function toggle(Request $request)
    {
        $request->validate([
            'bien_id' => 'required|exists:biens,id'
        ]);

        $user = Auth::user();
        $bienId = $request->bien_id;

        $favori = Favori::where('client_id', $user->id)
                        ->where('bien_id', $bienId)
                        ->first();

        if ($favori) {
            $favori->delete();
            return response()->json([
                'message' => 'Bien retiré des favoris.',
                'is_favorite' => false
            ], 200);
        } else {
            Favori::create([
                'client_id' => $user->id,
                'bien_id' => $bienId
            ]);
            return response()->json([
                'message' => 'Bien ajouté aux favoris.',
                'is_favorite' => true
            ], 201);
        }
    }
}
