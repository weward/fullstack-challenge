<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use App\Models\User;

class UserController extends Controller
{
 
    public function index()
    {   
        $users = User::select('id', 'name')->get();

        if ($users->count()) {
            foreach ($users as &$user) {
                $data = Redis::get("user_{$user->id}");
                $data = $data ? json_decode($data) : null;
                if ($data) {
                    $user->weather = $data->description->value;
                    $user->temperature = $data->temp->value;
                }
            }
        }

        return response()->json([
            'message' => 'all systems are a go',
            'users' => $users, 
        ], 200);
    }

    public function view(Request $request, User $user)
    {   
        $data = Redis::get("user_{$user->id}");
        if ($data) {
            $data = json_decode($data);
        }

        return response()->json($data, 200);
    }
}
