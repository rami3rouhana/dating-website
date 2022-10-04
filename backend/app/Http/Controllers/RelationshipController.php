<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Relationship;
use App\Models\User;

class RelationshipController extends Controller
{
    public function getUsers()
    {

        if (Auth::user()->intersted_in === 4) {
            $users = User::select(
                "*",
                User::raw("6371 * acos(cos(radians(" . Auth::user()->longitude . ")) 
                            * cos(radians(users.latitude)) 
                            * cos(radians(users.longitude) - radians(" . Auth::user()->longitude . ")) 
                            + sin(radians(" . Auth::user()->longitude . ")) 
                            * sin(radians(users.latitude))) AS distance")
            )
                ->where([
                    ['users.id', '<>', Auth::user()->id],
                    ['users.invisible', 0]
                ])
                ->whereNotIn('users.id', function ($query) {
                    $query->select('mates_id')
                        ->from('relationships')
                        ->where('favorite', 0)
                        ->orwhere('blocked', 1);
                })
                ->whereNotIn('users.id', function ($query) {
                    $query->select('users_id')
                        ->from('relationships')
                        ->where('favorite', 1)
                        ->orwhere('blocked', 1);
                })
                ->get();
        } else {
            $users = User::select(
                "*",
                User::raw("6371 * acos(cos(radians(" . Auth::user()->longitude . ")) 
                            * cos(radians(users.latitude)) 
                            * cos(radians(users.longitude) - radians(" . Auth::user()->longitude . ")) 
                            + sin(radians(" . Auth::user()->longitude . ")) 
                            * sin(radians(users.latitude))) AS distance")
            )
                ->where('id', '<>', Auth::user()->id)
                ->where('invisible', 0)
                ->where('gender', Auth::user()->intersted_in)
                ->whereNotIn('users.id', function ($query) {
                    $query->select('mates_id')
                        ->from('relationships')
                        ->where('favorite', 0)
                        ->orwhere('blocked', 1);
                })
                ->whereNotIn('users.id', function ($query) {
                    $query->select('users_id')
                        ->from('relationships')
                        ->where('favorite', 1)
                        ->orwhere('blocked', 1);
                })
                ->get();
        }

        if ($users) {
            return response()->json([
                'status' => 'success',
                'matches' => $users,
                'authorisation' => [
                    'token' => Auth::refresh(),
                    'type' => 'bearer',
                ]
            ]);
        }
    }

    public function getFavorites()
    {
        $favorites = Relationship::where([
            ['users_id', '=', Auth::user()->id],
            ['favorite', 1],
            ['blocked', 0]
        ])
            ->get();

        $result = [];

        foreach ($favorites as $favorite) {
            $result[] = Relationship::where([
                ['mates_id', '=', Auth::user()->id],
                ['users_id', '=', $favorite->mates_id],
                ['favorite', 1],
                ['blocked', 0]
            ])
                ->join('users', 'users.id', '=', 'Relationships.users_id')
                ->get();
        }

        return response()->json([
            'status' => 'success',
            'favorite' => $result,
            'authorisation' => [
                'token' => Auth::refresh(),
                'type' => 'bearer',
            ]
        ]);
    }

    public function getBlocked()
    {
        $blocked = Relationship::where([
            ['users_id', '=', Auth::user()->id],
            ['favorite', 0],
            ['blocked', 1]
        ])
            ->join('users', 'users.id', '=', 'Relationships.mates_id')
            ->get();


        return response()->json([
            'status' => 'success',
            'favorite' => $blocked,
            'authorisation' => [
                'token' => Auth::refresh(),
                'type' => 'bearer',
            ]
        ]);
    }

    public function toggleFavorites($id = null)
    {

        if ($id !== null) {
            $relationship = Relationship::where([
                ['mates_id', '=', $id],
                ['users_id', '=', Auth::user()->id]
            ])
                ->get()
                ->first();

            if (!$relationship) {
                $relationship = new Relationship;
                $relationship->users_id = Auth::user()->id;
                $relationship->mates_id = $id;
                $relationship->favorite = 1;
            } else {
                $relationship->favorite === 0 ? $relationship->favorite = 1 && $relationship->blocked = 0 : $relationship->favorite = 0 && $relationship->blocked = 1;
            }

            if ($relationship->save()) {
                return response()->json([
                    'status' => 'success',
                    'authorisation' => [
                        'token' => Auth::refresh(),
                        'type' => 'bearer',
                    ]
                ]);
            } else {
                return response()->json([
                    'status' => 'fail',
                    'error' => 'Failed to save data.'
                ]);
            }
        } else {
            return response()->json([
                'status' => 'fail',
                'error' => 'User ID null.'
            ]);
        }
    }

    public function toggleBlock($id = null)
    {

        if ($id !== null) {
            $relationship = Relationship::where([
                ['mates_id', '=', $id],
                ['users_id', '=', Auth::user()->id]
            ])
                ->get()
                ->first();

            if (!$relationship) {
                $relationship = new Relationship;
                $relationship->users_id = Auth::user()->id;
                $relationship->mates_id = $id;
                $relationship->blocked = 1;
            } else {
                $relationship->blocked === 0 ?
                    $relationship->blocked = 1 && $relationship->favorite = 0 :
                    $relationship->blocked = 0 && $relationship->favorite = 1;
            }

            if ($relationship->save()) {
                return response()->json([
                    'status' => 'success',
                    'authorisation' => [
                        'token' => Auth::refresh(),
                        'type' => 'bearer',
                    ]
                ]);
            } else {
                return response()->json([
                    'status' => 'fail',
                    'error' => 'Failed to save data.'
                ]);
            }
        } else {
            return response()->json([
                'status' => 'fail',
                'error' => 'User ID null.'
            ]);
        }
    }
}
