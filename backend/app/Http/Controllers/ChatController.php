<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\User;
use App\Models\Conversation;
use App\Http\Requests\CreateConversationInput;
use App\Http\Requests\DeleteConversationInput;

class ChatController extends Controller
{
    public function index() 
    {
        $chat = '';
        $user_id = auth()->user()->id;
        $chat = User::find($user_id)->conversations->sortByDesc('updated_at')->values()->load('lastMessage', 'users', 'team', 'messages');
        return Inertia::render('Chat/Chat', [
            'currentConversation' => '',
            'chatSource' => $chat,
            'fromProfile' => false,
        ]);        
    }

    public function store(CreateConversationInput $request)
    {
        $user_id = auth()->user()->id;
        $userConversations = User::find($user_id)->conversations->where('team_id', null)->pluck('id');
        $matchedConversation = User::find($request->profile_id)->conversations->whereIn('id', $userConversations);
        $isMatched = $matchedConversation->isNotEmpty();
        if($isMatched) {
            $currentConversation = $matchedConversation->first();
        } else {
            $currentConversation = Conversation::create(['team_id' => null]);
            $currentConversation->users()->attach([
                auth()->user()->id, $request->profile_id
            ]);
        }
        $currentConversation->load('lastMessage', 'users', 'team', 'messages');
        $chat = User::find($user_id)->conversations->sortByDesc('updated_at')->values()->load('lastMessage', 'users', 'team', 'messages');
        
        return Inertia::render('Chat/Chat', [
            'currentConversation' => $currentConversation,
            'chatSource' => $chat,
            'fromProfile' => true,
        ]);     
    }

    public function delete(DeleteConversationInput $request)
    {
        $conversation_id = $request->conversation_id;
        Conversation::with('users')->find($conversation_id)->delete();
        $user_id = auth()->user()->id;
        $chat = User::find($user_id)->conversations->sortByDesc('updated_at')->values()->load('lastMessage', 'users', 'team', 'messages');
        return ['chat' => $chat, 'conversation_id' => $conversation_id];
    }
}
