<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function getUsers()
    {
        $users = User::where('id', '!=', Auth::id())
            ->select('id', 'name', 'email')
            ->withCount(['receivedMessages' => function ($query) {
                $query->where('is_read', false)
                    ->where('sender_id', Auth::id());
            }])
            ->get();

        return response()->json($users);
    }

    public function getMessages(User $user)
    {
        $messages = Chat::where(function ($query) use ($user) {
            $query->where('sender_id', Auth::id())
                ->where('receiver_id', $user->id);
        })->orWhere(function ($query) use ($user) {
            $query->where('sender_id', $user->id)
                ->where('receiver_id', Auth::id());
        })
            ->with(['sender:id,name']) 
            ->orderBy('created_at', 'asc')
            ->get()
            ->map(function ($message) {
                return [
                    'id' => $message->id,
                    'sender_id' => $message->sender_id,
                    'receiver_id' => $message->receiver_id,
                    'message' => $message->message,
                    'sender_name' => $message->sender->name, 
                    'created_at' => $message->created_at
                ];
            });

        return response()->json($messages);
    }

    public function sendMessage(Request $request, User $user)
    {
        $request->validate([
            'message' => 'required|string|max:1000'
        ]);

        $chat = Chat::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $user->id,
            'message' => $request->message,
            'is_read' => false
        ]);

        return response()->json($chat);
    }

    public function markAsRead(User $user)
    {
        Chat::where('sender_id', $user->id)
            ->where('receiver_id', Auth::id())
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return response()->json(['success' => true]);
    }
}
