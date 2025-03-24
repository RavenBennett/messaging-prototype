<?php

use App\Repositories\RedisUsersWithMessagesRepository;
use Illuminate\Support\Facades\Redis;

beforeEach(function () {
    $this->usersWithMessagesRepository = new RedisUsersWithMessagesRepository();
    Redis::flushall();
    $this->key = 'active_users';
});

afterAll(function () {
    Redis::flushall();
});


test('can add user to set', function () {
    $userId = 1;

    expect(Redis::sismember($this->key, $userId))->toBeFalse();

    $this->usersWithMessagesRepository->create($userId);

    expect(Redis::sismember($this->key, $userId))->toBeTrue();
});

test('can see if user exists in set', function () {
    $userId = 1;

    expect($this->usersWithMessagesRepository->exists($userId))->toBeFalse();

    Redis::sadd($this->key, $userId);

    expect($this->usersWithMessagesRepository->exists($userId))->toBeTrue();
});

test('can delete user from set', function () {
    $userId = 1;

    expect(Redis::sismember($this->key, $userId))->toBeFalse();
    Redis::sadd($this->key, $userId);
    expect(Redis::sismember($this->key, $userId))->toBeTrue();

    $this->usersWithMessagesRepository->delete($userId);

    expect(Redis::sismember($this->key, $userId))->toBeFalse();
});

test('can get all users from set', function () {
    $userIds = ['1', '2', '3', '4', '5', '6', '7','8','9'];

    expect($this->usersWithMessagesRepository->all())->toBeEmpty();

    foreach ($userIds as $userId) {
        Redis::sadd($this->key, $userId);
    }

    expect($this->usersWithMessagesRepository->all())->toBe($userIds);
});
