<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ImageInputValidation;
use Intervention\Image\Facades\Image;
use App\Models\Message;
use App\Models\Conversation;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\ShowImageValidation;

class ImageController extends Controller
{
    public function show(ShowImageValidation $request)
    {
        $message = Conversation::find($request->conversation_id)->messages->where('image_path', $request->image_path);
        if($message->isEmpty()) {
            return false;
        }

        return response()->file(Storage::disk('private')->path($request->image_path));
    }

    public function store(ImageInputValidation $request)
    {
        if($request->is_image === "true") {
            $is_image = true;
        } else {
            return false;
        }
        $image_path = $request->file('image')->store('uploads-in-chat', 'private');
        $image = Image::make(Storage::disk('private')->path($image_path))->orientate()->fit(600, 600);
        $image->save();
        $message = Message::create([
            'sender_id' => (int) $request->sender_id,
            'message' => '[image]',
            'is_image' => $is_image,
            'image_path' => $image_path,
            'conversation_id' => (int) $request->currentChatId,
            'conversation_user_id' => Conversation::find((int) $request->currentChatId)->users->where('id', $request->sender_id)->first()->pivot->id,
        ]);
        $chat = auth()->user()->conversations->sortByDesc('updated_at')->values()->load('lastMessage', 'users', 'team', 'messages');
        return ['chat' => $chat, 'message' => $message];
    }
}
