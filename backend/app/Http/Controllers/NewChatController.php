<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NewChatController extends Controller
{
    public function index()
    {
        $chat = auth()->user()->conversations->sortByDesc('updated_at')->values()->load('lastMessage', 'users', 'team', 'messages');
        return $chat;
    }
}
