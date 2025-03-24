<?php

use App\Managers\UserMessageManager;
use App\Repositories\MessageRepositoryInterface;
use App\Repositories\UsersWithMessagesRepositoryInterface;


beforeEach(function () {
    $this->userRepositoryMock = Mockery::mock(UsersWithMessagesRepositoryInterface::class);
    $this->messageRepositoryMock = Mockery::mock(MessageRepositoryInterface::class);

    $this->manager = new UserMessageManager($this->userRepositoryMock, $this->messageRepositoryMock);
});

test('can add a message', function () {
    $userId = '1';
    $value = 'Hello world';

    $this->messageRepositoryMock
        ->shouldReceive('create')
        ->once()
        ->with($userId, Mockery::type('string'), $value)
        ->andReturn(true);

    $this->userRepositoryMock->shouldReceive('exists')
        ->once()
        ->with($userId)
        ->andReturn(false);

    $this->userRepositoryMock
        ->shouldReceive('create')
        ->once()
        ->with($userId)
        ->andReturn(true);

    $uuid = $this->manager->addMessage($userId, $value);
    expect($uuid)->toBeString()->not->toBeEmpty();
});

test('can remove a message', function () {
    $userId = '1';
    $uuid = 'uuid-1234';

    $this->messageRepositoryMock
        ->shouldReceive('delete')
        ->once()
        ->with($userId, $uuid)
        ->andReturn(true);

    $this->messageRepositoryMock
        ->shouldReceive('findByUserId')
        ->once()
        ->with($userId)
        ->andReturn(['another message']);

    $this->userRepositoryMock
        ->shouldNotHaveBeenCalled();

    expect($this->manager->removeMessage($userId, $uuid))->toBeTrue();
});

test('can remove a user when removing a message', function () {
    $userId = '1';
    $uuid = 'uuid-1234';

    $this->messageRepositoryMock
        ->shouldReceive('delete')
        ->once()
        ->with($userId, $uuid)
        ->andReturn(true);

    $this->messageRepositoryMock
        ->shouldReceive('findByUserId')
        ->once()
        ->with($userId)
        ->andReturn([]);

    $this->userRepositoryMock
        ->shouldReceive('delete')
        ->once()
        ->with($userId)
        ->andReturn(true);

    expect($this->manager->removeMessage($userId, $uuid))->toBeTrue();
});

test('can get a message', function () {
    $userId = '1';
    $uuid = 'uuid-1234';
    $this->messageRepositoryMock
        ->shouldReceive('findByUuid')
        ->once()
        ->with($userId, $uuid)
        ->andReturn('Hello world');

    expect($this->manager->getMessage($userId, $uuid))->toBe('Hello world');
});

test('can get all messages', function () {
    $array1 = [
        'uuid-1234' => 'Hello, world!',
        'uuid-5678' => 'Hi again!',
    ];
    $array2 = [
        'uuid-9999' => 'Just me.'
    ];
    $array3 = [
        'uuid-6382' => 'Is this thing on?',
        'uuid-8432' => 'Hellooooo',
        'uuid-3012' => 'Ok I give up.',
    ];

    $this->userRepositoryMock
        ->shouldReceive('all')
        ->once()
        ->andReturn(['1', '2', '3']);

    $this->messageRepositoryMock
        ->shouldReceive('findByUserId')
        ->times(3)
        ->andReturn($array1, $array2, $array3);

    $mergedArray['1'] = $array1;
    $mergedArray['2'] = $array2;
    $mergedArray['3'] = $array3;

    expect($this->manager->getAllMessages())->toBe($mergedArray);
});
