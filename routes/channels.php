<?php

use App\Models\User;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('user.{userId}', function (User $user, $userId) {
    return (int) $user->id === (int) $userId;
});

Broadcast::channel('message.received', function ($user) {
    return $user->can('approve message');
});

Broadcast::channel('online', function (User $user) {
    return ['name' => $user->name, 'id' => $user->id];
});
