<?php

use App\Repositories\RedisMessageRepository;
use Illuminate\Support\Facades\Redis;

beforeEach(function () {
    $this->messageRepository = new RedisMessageRepository();
    Redis::flushall();
});

afterAll(function () {
    Redis::flushall();
});

test('can create a message in Redis', function () {
    $userId = 1;
    $messageId = 'uuid-1234';
    $messageValue = 'Hello World!';

    expect(Redis::hget("user:{$userId}:messages", $messageId))->toBeFalse();

    $this->messageRepository->create($userId, $messageId, $messageValue);

    expect(Redis::hget("user:{$userId}:messages", $messageId))->toBe($messageValue);
});

test('can get all messages for user id', function () {
    $userId = 1;
    $messages = [
        'uuid-1234' => 'Hello, world!',
        'uuid-5678' => 'Hi again!',
    ];

    expect($this->messageRepository->findByUserId($userId))->toBeEmpty();

    foreach ($messages as $uuid => $message) {
        $this->messageRepository->create($userId, $uuid, $message);
    }

    expect($this->messageRepository->findByUserId($userId))->toBe($messages);
});

test('can get a message by uuid', function () {
    $userId = 1;
    $messages = [
        'uuid-1234' => 'Hello, world!',
        'uuid-5678' => 'Hi again!',
    ];

    expect($this->messageRepository->findByUuid($userId,'uuid-5678'))->toBeNull();

    foreach ($messages as $uuid => $message) {
        $this->messageRepository->create($userId, $uuid, $message);
    }

    expect($this->messageRepository->findByUuid($userId, 'uuid-5678'))->toBe('Hi again!');
});

test('can delete a message', function () {
    $userId = 1;
    $messageId = 'uuid-1234';
    $messageValue = 'Hello World!';

    $this->messageRepository->create($userId, $messageId, $messageValue);

    expect(Redis::hget("user:{$userId}:messages", $messageId))->toBe($messageValue);

    $this->messageRepository->delete($userId, $messageId);

    expect(Redis::hget("user:{$userId}:messages", $messageId))->toBeFalse();
});




