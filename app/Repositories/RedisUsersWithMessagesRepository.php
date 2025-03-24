<?php

namespace App\Repositories;

use Illuminate\Support\Facades\Redis;
class RedisUsersWithMessagesRepository implements UsersWithMessagesRepositoryInterface
{
    private $key = 'active_users';
    public function create(string $userId) : bool
    {
        return Redis::sadd($this->key, $userId) === 1;
    }
    public function exists(string $userId) : bool
    {
        return Redis::sismember($this->key, $userId);
    }
    public function all() : array
    {
        return Redis::smembers($this->key);
    }
    public function delete(string $userId): bool
    {
        return Redis::srem($this->key, $userId) === 1;
    }
}
