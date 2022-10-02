<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use App\Models\Message;

class MessageController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function sendMessage(Request $request)
    {
        $message = new Message;

        $message->users_id = Auth::user()->id;
        $message->message = Crypt::encryptString($request->message);
        $message->mates_id = $request->mates_id;

        if ($message->save()) {
            return response()->json([
                'status' => 'success',
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
                ->get();
            
            foreach($messages as $message){
                print_r($message->message = Crypt::decryptString($message->message)) ;
            }

            if (count($messages)>0) {
                return response()->json([
                    'status' => 'success',
                    'messages' => $messages,
                    'authorisation' => [
                        'token' => Auth::refresh(),
                        'type' => 'bearer',
                    ]
                ]);
            }
        }
    }
}
