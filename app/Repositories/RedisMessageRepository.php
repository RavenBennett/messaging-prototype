<?php

namespace App\Repositories;

use Illuminate\Support\Facades\Redis;
class RedisMessageRepository implements MessageRepositoryInterface
{
    private $prefix = 'user';
    private $suffix  = 'messages';

    public function create(string $userId, string $uuid, string $value) : bool
    {
        $key = "{$this->prefix}:{$userId}:{$this->suffix}";
        $result = Redis::hset($key, $uuid, $value);
        if(!$result) {
            return false;
        }

        $ttlSeconds = 30 * 24 * 60 * 60; // 30 days in seconds
        Redis::expire($key, $ttlSeconds);
        return true;
    }

    public function findByUserId(string $userId): array
    {
        $key = "{$this->prefix}:{$userId}:{$this->suffix}";
        return Redis::hgetall($key);
    }
    public function findByUuid(string $userId, string $uuid): ?string
    {
        $key = "{$this->prefix}:{$userId}:{$this->suffix}";
        $message = Redis::hget($key, $uuid);
        return $message === false ? null : $message;
    }
    public function delete(string $userId, string $uuid): bool
    {
        $key = "{$this->prefix}:{$userId}:{$this->suffix}";
        return Redis::hdel($key, $uuid) === 1;
    }
}
