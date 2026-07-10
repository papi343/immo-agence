<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Message;
use App\Http\Requests\MessageRequest;
use App\Http\Resources\MessageResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    /**
     * Display a listing of messages (Admin & Agent only).
     */
    public function index()
    {
        $user = Auth::user();
        if ($user->role === 'client') {
            return response()->json(['message' => 'Non autorisé.'], 403);
        }

        // Si l'utilisateur est un agent, il ne voit que les messages liés à ses propres biens
        if ($user->role === 'agent') {
            $messages = Message::whereHas('bien', function ($query) use ($user) {
                $query->where('agent_id', $user->id);
            })->orWhereNull('bien_id')->with('bien')->latest()->get();
        } else {
            // Admin voit tout
            $messages = Message::with('bien')->latest()->get();
        }

        return MessageResource::collection($messages);
    }

    /**
     * Store a newly created contact message (Public).
     */
    public function store(MessageRequest $request)
    {
        $data = $request->validated();
        
        $message = Message::create($data);
        $message->load('bien');

        return response()->json([
            'message' => 'Votre message a été envoyé avec succès. Notre équipe vous contactera sous peu.',
            'data' => new MessageResource($message)
        ], 201);
    }

    /**
     * Display the specified message.
     */
    public function show(Message $message)
    {
        $user = Auth::user();
        if ($user->role === 'client') {
            return response()->json(['message' => 'Non autorisé.'], 403);
        }

        if ($user->role === 'agent' && $message->bien_id) {
            if ($message->bien->agent_id !== $user->id) {
                return response()->json(['message' => 'Non autorisé.'], 403);
            }
        }

        $message->load('bien');
        return new MessageResource($message);
    }

    /**
     * Remove the specified message.
     */
    public function destroy(Message $message)
    {
        $user = Auth::user();
        if ($user->role === 'client') {
            return response()->json(['message' => 'Non autorisé.'], 403);
        }

        $message->delete();

        return response()->json([
            'message' => 'Message supprimé avec succès.'
        ], 200);
    }
}
