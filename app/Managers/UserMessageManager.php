<?php

namespace App\Managers;

use App\Repositories\MessageRepositoryInterface;
use App\Repositories\OfflineUserBroadcastRepositoryInterface;
use App\Repositories\UsersWithMessagesRepositoryInterface;
use Ramsey\Uuid\Guid\Guid;

class UserMessageManager
{
    protected $userRepository;
    protected $messageRepository;
    protected $offlineUserBroadcastRepository;
    protected $userId;

    public function __construct(
        UsersWithMessagesRepositoryInterface $userRepository,
        MessageRepositoryInterface $messageRepository,
        OfflineUserBroadcastRepositoryInterface $offlineUserBroadcastRepository
    ) {
        $this->userRepository = $userRepository;
        $this->messageRepository = $messageRepository;
        $this->offlineUserBroadcastRepository = $offlineUserBroadcastRepository;
    }

    public function setUserId($userId)
    {
        $this->userId = $userId;
        return $this;
    }

    public function getAllMessages() : array
    {
        $users = $this->userRepository->all();
        $messages = [];

        foreach ($users as $user) {
            $userMessages  = $this->messageRepository->findByUserId($user);
            $messages[$user] =  $userMessages;
        }

        return $messages;
    }

    public function addMessage(string $userId, string $value) : ?string
    {
        $uuid = Guid::uuid4();
        $uuidString = $uuid->toString();

        if(!$this->messageRepository->create($userId, $uuidString, $value)) {
            return null;
        }

        if(!$this->userRepository->exists($userId))
        {
            $sucsess = $this->userRepository->create($userId);
            if(!$sucsess)
            {
                $this->messageRepository->delete($userId, $uuidString);
                return null;
            }
        }

        return $uuidString;
    }

    public function removeMessage(string $userId, string $uuid) : bool
    {
        if(!$this->messageRepository->delete($userId, $uuid)) {
            return false;
        }

        $userMessages = $this->messageRepository->findByUserId($userId);
        if(empty($userMessages)) {
            $this->userRepository->delete($userId);
        }

        return true;
    }

    public function getMessage(string $userId, string $uuid) : ?string
    {
        return $this->messageRepository->findByUuid($userId, $uuid);
    }

    public function storeBroadcast(string $userId, $uuid, string $value) : bool
    {
        return $this->offlineUserBroadcastRepository->create($userId, $uuid, $value);
    }

    public function deleteBroadcast(string $userId, string $uuid) : bool
    {
        return $this->offlineUserBroadcastRepository->delete($userId, $uuid);
    }

    public function getMissedBroadcasts(string $userId) : array
    {
        return $this->offlineUserBroadcastRepository->all($userId);
    }
}
