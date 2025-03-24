<?php

use App\Models\User;
use function Pest\Laravel\postJson;
use Illuminate\Support\Facades\Redis;

beforeEach(function () {
    Redis::flushall();
});

afterAll(function () {
    Redis::flushall();
});

test('user can send a message successfully', function () {

    $user = User::factory()->create();
    $token = $user->createToken('TestToken')->plainTextToken;

    $response = postJson('/api/user/message', [
            'message' => 'Hello, this is a test message.',
        ],
        [
            'Authorization' => 'Bearer ' . $token,
        ]
    );
    expect($response->status())->toBe(202)
        ->and($response->json('success'))->toBeTrue()
        ->and($response->json('message'))->toBe('Request received and is being processed')
        ->and($response->json('uuid'))->toBeString()->not->toBeEmpty();
});
