<?php

declare(strict_types=1);

namespace App\User\UseCase\CreateUser;

use App\User\Entity\User;
use App\User\DTO\UserResult;
use App\User\UseCase\UserRepository;

class CreateUser
{
    public function __construct(private UserRepository $userRepository)
    {
    }

    public function create(CreateUserDTO $createUserDTO): UserResult
    {
        $user = new User(...$createUserDTO->toArray());
        $this->userRepository->save($user);

        return new UserResult(...$user->toArray());
    }
}