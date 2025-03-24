<?php

namespace App\Repositories;

use Illuminate\Support\Facades\Redis;
class RedisOfflineUserBroadcastRepository implements OfflineUserBroadcastRepositoryInterface
{
    private $prefix = 'user';
    private $suffix = 'broadcast';

    public function create(string $userId, string $uuid, string $value) : bool
    {
        $key = "{$this->prefix}:{$userId}:{$this->suffix}";
        $result = Redis::hset($key, $uuid, $value);
        if(!$result) {
            return false;
        }

        return true;
    }

    public function delete(string $userId, $uuid): bool
    {
        $key = "{$this->prefix}:{$userId}:{$this->suffix}";
        return Redis::hdel($key, $uuid) === 1;
    }

    public function all(string $userId): array
    {
        $key = "{$this->prefix}:{$userId}:{$this->suffix}";
        return Redis::hgetall($key);
    }

}
