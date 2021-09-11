<?php

namespace App\Tests\Unit\User;

use App\User\Entity\User;

class UserFactory
{
    public static function createUser(array $userData): User
    {
        return new User(...$userData);
    }

    public static function createUserWithId(int $userId, array $userData): User
    {
        $user = self::createUser($userData);
        (fn() => $this->id = $userId)->call($user);

        return $user;
    }
}