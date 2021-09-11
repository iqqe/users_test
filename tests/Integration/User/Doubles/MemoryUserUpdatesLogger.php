<?php

namespace App\Tests\Integration\User\Doubles;

use App\User\Model\UserUpdatedFields;
use App\User\UseCase\UserUpdatesLogger;

class MemoryUserUpdatesLogger implements UserUpdatesLogger
{
    private array $updatedFields = [];

    public function log(UserUpdatedFields $userUpdatedFields): void
    {
        $this->updatedFields = $userUpdatedFields->toArray();
    }

    public function getValues(): array
    {
        return $this->updatedFields;
    }
}