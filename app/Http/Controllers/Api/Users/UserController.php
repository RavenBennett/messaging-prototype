<?php

namespace App\Http\Controllers\Api\Users;

use App\Http\Controllers\Controller;
use App\Managers\UserMessageManager;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    public function all(): JsonResponse
    {
        $users = User::select('id', 'name')->get();
        return response()->json($users);
    }

    public function user(Request $request)
    {
        return $request->user();
    }
}
