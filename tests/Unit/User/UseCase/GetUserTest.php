<?php

namespace App\Tests\Unit\User\UseCase;

use App\Tests\Unit\User\UserFactory;
use App\User\DTO\UserResult;
use App\User\UseCase\GetUser;
use App\User\UseCase\UserNotFoundException;
use PHPUnit\Framework\TestCase;
use App\Tests\Unit\User\UseCase\RepositoryStub;

class GetUserTest extends TestCase
{
    public function testCanGetExistingUser(): void
    {
        $user = UserFactory::createUserWithId(1, ['name' => 'test', 'email' => 'test@test.test']);
        $repository = new RepositoryStub([$user]);
        $getUser = new GetUser($repository);

        $userResult = $getUser->getById(1);
        $userResultData = $userResult->jsonSerialize();

        $this->assertInstanceOf(UserResult::class, $userResult);
        $this->assertEquals(1, $userResultData['id']);
    }

    public function testNotFoundIsThrownIfNoExistingUser(): void
    {
        $repository = new RepositoryStub();
        $getUser = new GetUser($repository);

        $this->expectException(UserNotFoundException::class);

        $getUser->getById(1);
    }
}