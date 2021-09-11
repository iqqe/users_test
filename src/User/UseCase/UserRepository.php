<?php

declare(strict_types=1);

namespace App\User\UseCase;

use App\User\Entity\User;

interface UserRepository
{
    public function find(int $id): ?User;

    /**
     * @throws UniqueConstraintViolationException
     */
    public function save(User $user): void;
}