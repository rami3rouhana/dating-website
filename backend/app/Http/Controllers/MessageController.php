<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use App\Models\Message;

class MessageController extends Controller
{
    public function sendMessage(Request $request)
    {
        $message = new Message;

        $message->users_id = Auth::user()->id;
        $message->message = Crypt::encryptString($request->message);
        $message->mates_id = $request->mates_id;

        if ($message->save()) {
            return response()->json([
                'status' => 'success',
                'user' => Auth::user(),
                'authorisation' => [
                    'token' => Auth::refresh(),
                    'type' => 'bearer',
                ]
            ]);
        }
    }

    public function receiveMessages($id = null)
    {
        if ($id !== null) {
            $messages = Message::where([
                ['mates_id', '=', Auth::user()->id],
                ['users_id', '=', $id]
            ])
                ->orwhere([
                    ['mates_id', '=', $id],
                    ['users_id', '=', Auth::user()->id]
                ])
                ->join('users', 'users.id', '=', 'messages.mates_id')
                ->get();

            foreach ($messages as $message) {
                $message->message = Crypt::decryptString($message->message);
            }

            return response()->json([
                'status' => 'success',
                'messages' => $messages,
                'authorisation' => [
                    'token' => Auth::refresh(),
                    'type' => 'bearer',
                ]
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'messages' => 'Id not set',
            ]);
        }
    }
}
