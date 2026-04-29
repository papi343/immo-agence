<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\AuthRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;
class AuthController extends Controller
{
  // register api 
    public function register(AuthRequest $request)
    {
        $date = $request->validated();
        $user = User::create($date);
        $token = $user->createToken('auth_Token')->plainTextToken;
        return response()->json([
            'message' => 'Utilisateur créé avec succès',
            'user' => $user,
            'token' => $token,
        ], 201);
    }



    //login api
    public function login(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|email',
            'password'=>'required',
        ]);
        if(!Auth::attempt($data))
        {
            return response()->json([
                'message'=> 'email ou mot de passe incorrect',
            ],401);
        }
        $user = Auth::user();
        $token = $user->createToken('auth_Token')->plainTextToken;

        return response()->json([
            'message' => 'Utilisateur connecté avec succès',
            'user' => $user,
            'token' => $token,
        ], 200);
    }


    //logout api
    public function logout(Request $request)
    {
        $user = $request->user();
        $user->currentAccessToken()->delete();
        return response()->json([
            'message' => 'Utilisateur deconnecte avec succès',
        ], 200);    
    }


}
