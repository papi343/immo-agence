<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Get the authenticated user's profile.
     */
    public function profile()
    {
        return new UserResource(Auth::user());
    }

    /**
     * Update the authenticated user's profile.
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $data = $request->validate([
            'nom' => 'sometimes|required|string|max:255',
            'prenom' => 'sometimes|required|string|max:255',
            'tel' => 'sometimes|required|string|max:255',
            'email' => [
                'sometimes',
                'required',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id),
            ],
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        if (!isset($data['password']) || empty($data['password'])) {
            unset($data['password']);
        }

        $user->update($data);

        return response()->json([
            'message' => 'Profil mis à jour avec succès.',
            'user' => new UserResource($user)
        ], 200);
    }

    /**
     * List all agents (useful for clients to choose or for admin assignations).
     */
    public function agents()
    {
        $agents = User::whereIn('role', ['agent', 'admin'])->get();
        return UserResource::collection($agents);
    }

    /**
     * List all clients (useful for agents/admin to assign owners/buyers).
     */
    public function clients()
    {
        $clients = User::where('role', 'client')->get();
        return UserResource::collection($clients);
    }
}
