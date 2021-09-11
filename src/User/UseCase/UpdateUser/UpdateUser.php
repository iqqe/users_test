<?php

declare(strict_types=1);

namespace App\User\UseCase\UpdateUser;

use App\User\DTO\UserResult;
use App\User\UseCase\UserNotFoundException;
use App\User\UseCase\UserRepository;
use App\User\Event\UserUpdatedEvent;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class UpdateUser
{
    public function __construct(private UserRepository $userRepository, private EventDispatcherInterface $dispatcher)
    {
    }

    public function update(int $id, UpdateUserDTO $updateUserDTO): UserResult
    {
        $user = $this->userRepository->find($id);
        if (empty($user)) {
            throw new UserNotFoundException("Can not find user with {$id}");
        }

        $updatedFields = $user->update($updateUserDTO->toArray());

        if (!$updatedFields->isEmpty()) {
            $this->userRepository->save($user);
            $userUpdatedEvent = new UserUpdatedEvent($updatedFields);
            $this->dispatcher->dispatch($userUpdatedEvent);
        }

        return new UserResult(...$user->toArray());
    }
}