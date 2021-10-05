<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\MessageInputValidation;
use App\Models\Message;
use App\Models\Conversation;
use App\Models\User;
use App\Http\Requests\DeleteMessageInput;

class MessageController extends Controller
{
    public function index()
    {
        $chat = auth()->user()->conversations->sortByDesc('updated_at')->values()->load('lastMessage', 'users', 'team', 'messages');
        return $chat;
    }

    public function store(MessageInputValidation $request)
    {
        if($request->messageForm['is_image']) {
            return false;
        }
        if(!auth()->user()->conversations()->get()->contains($request->messageForm['currentChatIdToSubmit'])) {
            return false;
        }
        $message = Message::create([
            'sender_id' => $request->messageForm['sender_id'],
            'message' => $request->messageForm['message'],
            'is_image' => $request->messageForm['is_image'],
            'conversation_id' => $request->messageForm['currentChatIdToSubmit'],
            'conversation_user_id' => Conversation::find($request->messageForm['currentChatIdToSubmit'])->users->where('id', $request->messageForm['sender_id'])->first()->pivot->id,
        ]);
        $chat = auth()->user()->conversations->sortByDesc('updated_at')->values()->load('lastMessage', 'users', 'team', 'messages');
        return ['chat' => $chat, 'message' => $message];
    }

    public function delete(DeleteMessageInput $request)
    {
        Message::find($request->message_id)->delete();
        $user_id = auth()->user()->id;
        $chat = User::find($user_id)->conversations->sortByDesc('updated_at')->values()->load('lastMessage', 'users', 'team', 'messages');
        return $chat;
    }
}
