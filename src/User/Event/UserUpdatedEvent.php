<?php

namespace App\User\Event;

use App\User\Model\UserUpdatedFields;
use Symfony\Contracts\EventDispatcher\Event;

class UserUpdatedEvent extends Event
{
    public const NAME = 'user.updated';

    public function __construct(private UserUpdatedFields $userUpdatedFields)
    {
    }

    public function getUserUpdatedFields(): UserUpdatedFields
    {
        return $this->userUpdatedFields;
    }
}