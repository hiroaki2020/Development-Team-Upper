<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Contracts\Auth\MustVerifyEmail;

class UserEmailController extends Controller
{
    public function update(Request $request)
    {
        $user = $request->user();
        $input = (array) $request->all();
        Validator::make($input, [
            '_method' => ['required', 'string', 'in:PUT'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
        ])->validateWithBag('updateEmail');

        if ($input['email'] !== $user->email && $user instanceof MustVerifyEmail) {
            $user->forceFill([
                'email' => $input['email'],
                'email_verified_at' => null,
            ])->save();
    
            $user->sendEmailVerificationNotification();
        } else {
            $user->forceFill([
                'email' => $input['email'],
            ])->save();
        }

        return $request->wantsJson()
                    ? new JsonResponse('', 200)
                    : back()->with('status', 'email-updated');
    }
}
