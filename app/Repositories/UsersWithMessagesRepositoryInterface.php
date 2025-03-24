<?php

namespace App\Repositories;
interface UsersWithMessagesRepositoryInterface
{
    public function create(string $userId) : bool;
    public function exists(string $userId) : bool;
    public function all() : array;
    public function delete(string $userId): bool;
}
