<?php

namespace App\Http\Controllers;

use App\Events\MessageSent;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    // Renderiza a tela do chat com os contatos
    public function index()
    {
        $users = User::where('id', '!=', Auth::id())->get();
        return view('chat.index', compact('users'));
    }

    // Retorna o histórico de mensagens em JSON para o Alpine.js
    public function fetchMessages(User $user)
    {
        $messages = Message::where(function($query) use ($user) {
            $query->where('sender_id', Auth::id())
                  ->where('receiver_id', $user->id);
        })->orWhere(function($query) use ($user) {
            $query->where('sender_id', $user->id)
                  ->where('receiver_id', Auth::id());
        })->with('sender')->orderBy('created_at', 'asc')->get();

        return response()->json($messages);
    }

    // Salva a mensagem e spara o Evento Reverb
    public function store(Request $request, User $user)
    {
        $request->validate([
            'content' => 'required|string'
        ]);

        $message = Message::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $user->id,
            'content' => $request->content
        ]);

        // Dispara o WebSocket
        broadcast(new MessageSent($message))->toOthers();

        return response()->json($message->load('sender'));
    }
}
