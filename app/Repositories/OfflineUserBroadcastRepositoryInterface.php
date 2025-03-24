<?php

namespace App\Repositories;
interface OfflineUserBroadcastRepositoryInterface
{
    function create(string $userId, string $uuid, string $value) : bool;
    public function all(string $userId) : array;
    public function delete(string $userId, $uuid): bool;

}
