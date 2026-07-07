<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\API\BienController;
use App\Http\Controllers\API\VisiteController;
use App\Http\Controllers\API\TransactionController;
use App\Http\Controllers\API\MessageController;
use App\Http\Controllers\API\FavoriController;
use App\Http\Controllers\API\UserController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// --- Routes Publiques ---
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

// Consultation des biens (public)
Route::get('biens', [BienController::class, 'index']);
Route::get('biens/{bien}', [BienController::class, 'show']);

// Formulaire de contact public
Route::post('messages', [MessageController::class, 'store']);


// --- Routes Protégées (Sanctum) ---
Route::middleware('auth:sanctum')->group(function () {
    
    // Authentification
    Route::post('logout', [AuthController::class, 'logout']);
    
    // Profil Utilisateur
    Route::get('profile', [UserController::class, 'profile']);
    Route::put('profile', [UserController::class, 'updateProfile']);
    
    // Listes d'utilisateurs par rôle
    Route::get('users/agents', [UserController::class, 'agents']);
    Route::get('users/clients', [UserController::class, 'clients']);

    // Gestion des Biens (Agent & Admin)
    Route::post('biens', [BienController::class, 'store']);
    Route::post('biens/{bien}', [BienController::class, 'update']); // Utiliser POST avec _method=PUT pour supporter l'upload d'images
    Route::delete('biens/{bien}', [BienController::class, 'destroy']);

    // Favoris (Client)
    Route::get('favoris', [FavoriController::class, 'index']);
    Route::post('favoris/toggle', [FavoriController::class, 'toggle']);

    // Visites (Planification & Suivi)
    Route::apiResource('visites', VisiteController::class);

    // Transactions (Ventes & Locations)
    Route::get('transactions', [TransactionController::class, 'index']);
    Route::post('transactions', [TransactionController::class, 'store']);
    Route::get('transactions/{transaction}', [TransactionController::class, 'show']);
    Route::delete('transactions/{transaction}', [TransactionController::class, 'destroy']);

    // Messagerie interne (Consultation pour Agents & Admins)
    Route::get('messages', [MessageController::class, 'index']);
    Route::get('messages/{message}', [MessageController::class, 'show']);
    Route::delete('messages/{message}', [MessageController::class, 'destroy']);
    
});
