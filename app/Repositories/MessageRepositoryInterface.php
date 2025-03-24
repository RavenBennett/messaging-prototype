<?php

namespace App\Repositories;
interface MessageRepositoryInterface
{
    public function create(string $userId, string $uuid, string $value) : bool;
    public function findByUserId(string $userId) : array;
    public function findByUuid(string $userId, string $uuid) : ?string;
    public function delete(string $userId, string $uuid): bool;
}
