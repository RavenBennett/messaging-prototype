<?php

namespace App\Http\Controllers\Api\Users;

use App\Events\MessageProcessed;
use App\Http\Controllers\Controller;
use App\Managers\UserMessageManager;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class EventController extends Controller
{
    protected $messageManager;

    public function __construct(UserMessageManager $messageManager)
    {
        $this->messageManager = $messageManager;
    }

    public function missed() : JsonResponse {
        $user = auth()->user();

        $missedEvents =  $this->messageManager->getMissedBroadcasts($user->id);
        foreach ($missedEvents as $field => $value) {
            switch ($value) {
                case 'processed':
                    MessageProcessed::dispatch([
                        'userId' => $user->id,
                        'messageId' => $field
                    ]);
                    $this->messageManager->deleteBroadcast($user->id, $field);
                    break;
            }
        }

        return response()->json(true);
    }
}
