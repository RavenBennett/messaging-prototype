<?php

namespace App\Livewire\Admin;

use Livewire\Attributes\On;
use Livewire\Component;
use App\Managers\UserMessageManager;
use App\Events\MessageProcessed;
use \stdClass as stdClass;
use Carbon\Carbon;

class MessageList extends Component
{
    public array $messages;
    private $userId;

    public array $onlineUsers = [];

    public function mount(): void
    {
        $this->userId = auth()->id();
        $this->refreshMessages();
    }

    public function getManager()
    {
        return app(UserMessageManager::class)->setUserId($this->userId);
    }

    #[On('echo-presence:online,here')]
    public function usersHere($event): void
    {
        $this->onlineUsers = array_column($event, 'id');
    }

    #[On('echo-presence:online,joining')]
    public function usersJoin($event): void
    {
        $id = $event['id'];
        if(in_array($id, $this->onlineUsers)) {
            return;
        }

        $this->onlineUsers[] = $id;
    }

    #[On('echo-presence:online,leaving')]
    public function usersLeave($event): void
    {
        $this->onlineUsers = array_diff($this->onlineUsers, [$event['id']]);
    }


    #[On('echo:message,MessageReceived')]
    public function refreshMessages(): void
    {
        $rawMessages = $this->getManager()->getAllMessages();
        $this->messages = [];
        foreach($rawMessages as  $userId => $value)
        {
            $index = 0;
            $messages = [];
            $userName = '';
            foreach($value as $uuid => $data)
            {
                $message = json_decode($data);
                $userName = $message->userName;
                unset($message->userName);
                $message->uuid = $uuid;
                $message->formattedTime = $this->formatMessageTime($message->timeStamp);
                $messages[$index] = $message;
                $index++;
            }
            usort($messages, function($a, $b) {
                return $b->timeStamp - $a->timeStamp;
            });

            $tempUser = new stdClass();
            $tempUser->userId = $userId;
            $tempUser->userName = $userName;
            $tempUser->messages = $messages;
            $this->messages[] = $tempUser;
        }

        usort($this->messages, function($a, $b) {
            return strcmp(strtolower($a->userName), strtolower($b->userName));
        });
    }

    private function formatMessageTime($timestamp): string
    {
        return Carbon::parse($timestamp)
            ->timezone(date_default_timezone_get())
            ->format('d/m/Y H:i');
    }

    public function CompleteMessage(string $userId, string $messageId): void
    {
        $result = $this->getManager()->removeMessage($userId, $messageId);
        if(!$result)
        {
            $this->dispatch('flash', message: 'Failed to complete.', type: 'fail');
            return;
        }

        $message = [
            'userId' => $userId,
            'messageId' => $messageId,
        ];


        if(in_array((int)$userId, $this->onlineUsers))
        {
            MessageProcessed::dispatch($message);
        }
        else
        {
            $this->getManager()->storeBroadcast($userId, $messageId, 'processed');
        }

        $this->dispatch('flash', message: 'Message completed.', type: 'success');
        $this->refreshMessages();
    }

    public function render()
    {
        return view('livewire.admin.message-list');
    }


}
