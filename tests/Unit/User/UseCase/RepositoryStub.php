<?php

namespace App\Tests\Unit\User\UseCase;

use App\User\Entity\User;
use App\User\UseCase\UserRepository;

class RepositoryStub implements UserRepository
{
    public function __construct(private array $users = [])
    {
    }

    public function save(User $user): void
    {
        (fn() => $this->id = 1)->call($user);

        $this->users[] = $user;
    }

    public function find(int $id): ?User
    {
        foreach ($this->users as $user) {
            if ($user->toArray()['id'] === $id) {
                return $user;
            }
        }

        return null;
    }
}