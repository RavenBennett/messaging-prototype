<?php

namespace App\Http\Controllers\Api\Users;

use App\Events\MessageReceived;
use App\Http\Controllers\Controller;
use App\Managers\UserMessageManager;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class MessageController extends Controller
{
    protected $messageManager;

    public function __construct(UserMessageManager $messageManager)
    {
        $this->messageManager = $messageManager;
    }

    public function store(Request $request) : JsonResponse
    {

        $validated = $request->validate([
            'content' => 'required|string',
            'recipient_id' => 'required|integer|exists:users,id'
        ]);

        $user = auth()->user();

        $timestamp = now()->unix();
        $data = [
            'message' => $validated['content'],
            'timeStamp' => $timestamp,
            'userName' => $user->name,
            'recipient_id' => $validated['recipient_id']
        ];
        $uuid = $this->messageManager->addMessage($user->id, json_encode($data));

        if($uuid === null) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong',
            ], 500);
        }

        MessageReceived::dispatch();
        return response()->json([
            'success' => true,
            'message' => 'Request received and is being processed',
            'uuid'=> $uuid,
            'timestamp' => $timestamp,
        ], 202);
    }
}
