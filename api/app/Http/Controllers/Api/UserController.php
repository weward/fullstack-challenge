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
        $users = User::all();

        if ($users->count()) {
            foreach ($users as &$user) {
                $data = Redis::get("user_{$user->id}");
                $data = $data ? json_decode($data) : null;
                if ($data) {
                    $user->weather = $data->weather[0]->description;
                    $user->temperature = temperature($data->main->temp, 'fahrenheit');
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
        $data = $data ? json_decode($data) : null;

        return response()->json([
            'message' => 'User\'s Weather Data',
            'data' => $data, 
        ], 200);
    }
}
