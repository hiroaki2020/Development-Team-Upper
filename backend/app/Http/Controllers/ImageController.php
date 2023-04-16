<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ImageInputValidation;
use Intervention\Image\Facades\Image;
use App\Models\Message;
use App\Models\Conversation;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\ShowImageValidation;
use Illuminate\Http\File;
use Illuminate\Http\UploadedFile;

class ImageController extends Controller
{
    public function show(ShowImageValidation $request)
    {
        $message = Conversation::find($request->conversation_id)->messages->where('image_path', $request->image_path);
        if($message->isEmpty()) {
            return false;
        }

        $image_store = config('app.env') === 'production' || config('app.env') === 'testing' ? 's3' : 'private';
        $image = Storage::disk($image_store)->get($request->image_path);

        return $image;
    }

    public function store(ImageInputValidation $request)
    {
        if($request->is_image === "true") {
            $is_image = true;
        } else {
            return false;
        }
        
        $image = Image::make($request->image)->orientate()->fit(600, 600)->save();
        $tmpFile = new File($image->basePath());
        $file = new UploadedFile(
            $tmpFile->getPathname(),
            $tmpFile->getFilename(),
            $tmpFile->getMimeType(),
            0,
            false
        );
        $image_store = config('app.env') === 'production' || config('app.env') === 'testing' ? 's3' : 'private';
        $image_path = Storage::disk($image_store)->put('uploads-in-chat', $file);
        
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

