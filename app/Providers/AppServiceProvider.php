<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Repositories\OfflineUserBroadcastRepositoryInterface;
use App\Repositories\UsersWithMessagesRepositoryInterface;
use App\Repositories\MessageRepositoryInterface;
use App\Repositories\RedisOfflineUserBroadcastRepository;
use App\Repositories\RedisUsersWithMessagesRepository;
use App\Repositories\RedisMessageRepository;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(UsersWithMessagesRepositoryInterface::class, RedisUsersWithMessagesRepository::class);
        $this->app->bind(MessageRepositoryInterface::class, RedisMessageRepository::class);
        $this->app->bind(OfflineUserBroadcastRepositoryInterface::class, RedisOfflineUserBroadcastRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
