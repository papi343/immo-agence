<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Bien;
use App\Models\BienImage;
use App\Http\Requests\BienRequest;
use App\Http\Resources\BienResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BienController extends Controller
{
    /**
     * Display a listing of the properties (with advanced filtering).
     */
    public function index(Request $request)
    {
        $query = Bien::with(['agent', 'proprietaire', 'images', 'primaryImage']);

        // Filtrage par type
        if ($request->has('type')) {
            $query->where('type', $request->type);
        }

        // Filtrage par statut (a_vendre, a_louer...)
        if ($request->has('statut')) {
            $query->where('statut', $request->statut);
        }

        // Prix min / max
        if ($request->has('prix_min')) {
            $query->where('prix', '>=', $request->prix_min);
        }
        if ($request->has('prix_max')) {
            $query->where('prix', '<=', $request->prix_max);
        }

        // Surface min / max
        if ($request->has('surface_min')) {
            $query->where('surface', '>=', $request->surface_min);
        }
        if ($request->has('surface_max')) {
            $query->where('surface', '<=', $request->surface_max);
        }

        // Ville
        if ($request->has('ville')) {
            $query->where('ville', 'like', '%' . $request->ville . '%');
        }

        // Nombre de pièces, chambres, SDB
        if ($request->has('pieces')) {
            $query->where('pieces', '>=', $request->pieces);
        }
        if ($request->has('chambres')) {
            $query->where('chambres', '>=', $request->chambres);
        }
        if ($request->has('salles_de_bain')) {
            $query->where('salles_de_bain', '>=', $request->salles_de_bain);
        }

        // Recherche par mot clé dans le titre / description
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('titre', 'like', '%' . $search . '%')
                  ->orWhere('description', 'like', '%' . $search . '%');
            });
        }

        $biens = $query->latest()->paginate($request->input('per_page', 10));

        return BienResource::collection($biens);
    }

    /**
     * Store a newly created property.
     */
    public function store(BienRequest $request)
    {
        $data = $request->validated();
        
        // S'assurer que le features soit encodé si fourni
        if (isset($data['features'])) {
            $data['features'] = $data['features'];
        }

        $bien = Bien::create($data);

        // Upload des images
        if ($request->hasFile('images')) {
            $primaryIndex = $request->input('primary_image_index', 0);
            foreach ($request->file('images') as $index => $file) {
                $path = $file->store('biens', 'public');
                $bien->images()->create([
                    'image_path' => $path,
                    'is_primary' => ($index == $primaryIndex)
                ]);
            }
        }

        $bien->load(['agent', 'proprietaire', 'images', 'primaryImage']);

        return response()->json([
            'message' => 'Bien créé avec succès',
            'data' => new BienResource($bien)
        ], 201);
    }

    /**
     * Display the specified property.
     */
    public function show(Bien $bien)
    {
        $bien->load(['agent', 'proprietaire', 'images', 'primaryImage']);
        return new BienResource($bien);
    }

    /**
     * Update the specified property.
     */
    public function update(BienRequest $request, Bien $bien)
    {
        $data = $request->validated();

        $bien->update($data);

        // Si de nouvelles images sont uploadées
        if ($request->hasFile('images')) {
            $primaryIndex = $request->input('primary_image_index', 0);

            // Si on veut définir un nouveau lot d'images, on peut désactiver l'ancienne image principale
            if ($bien->images()->where('is_primary', true)->exists() && $primaryIndex === 0) {
                // Conserver l'ancienne image principale ou non ? 
                // C'est facultatif, selon le choix de l'utilisateur
            }

            foreach ($request->file('images') as $index => $file) {
                $path = $file->store('biens', 'public');
                
                // Si une image principale existe déjà et qu'on rajoute une principale, on ajuste
                $isPrimary = ($index == $primaryIndex);
                if ($isPrimary) {
                    $bien->images()->update(['is_primary' => false]);
                }

                $bien->images()->create([
                    'image_path' => $path,
                    'is_primary' => $isPrimary
                ]);
            }
        }

        // Option pour supprimer des images spécifiques
        if ($request->has('delete_images')) {
            $imageIds = $request->delete_images;
            foreach ($imageIds as $id) {
                $img = BienImage::where('bien_id', $bien->id)->find($id);
                if ($img) {
                    Storage::disk('public')->delete($img->image_path);
                    $img->delete();
                }
            }
        }

        $bien->load(['agent', 'proprietaire', 'images', 'primaryImage']);

        return response()->json([
            'message' => 'Bien mis à jour avec succès',
            'data' => new BienResource($bien)
        ], 200);
    }

    /**
     * Remove the specified property.
     */
    public function destroy(Bien $bien)
    {
        // Supprimer toutes les images du stockage
        foreach ($bien->images as $img) {
            Storage::disk('public')->delete($img->image_path);
        }

        $bien->delete();

        return response()->json([
            'message' => 'Bien supprimé avec succès'
        ], 200);
    }
}
