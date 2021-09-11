<?php

declare(strict_types=1);

namespace App\User\UseCase;

use App\User\DTO\UserResult;

class GetUser
{
    public function __construct(private UserRepository $userRepository)
    {
    }

    public function getById(int $id): UserResult
    {
        $user = $this->userRepository->find($id);

        if (empty($user)) {
            throw new UserNotFoundException("Can not find user with {$id}");
        }

        return new UserResult(...$user->toArray());
    }
}