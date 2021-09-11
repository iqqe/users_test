<?php

declare(strict_types=1);

namespace App\User\UseCase;

use App\User\Model\UserUpdatedFields;

interface UserUpdatesLogger
{
    public function log(UserUpdatedFields $userUpdatedFields): void;
}