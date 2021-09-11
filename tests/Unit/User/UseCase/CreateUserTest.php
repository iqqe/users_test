<?php

namespace App\Tests\Unit\User\UseCase;

use App\User\DTO\UserResult;
use App\User\UseCase\CreateUser\CreateUser;
use App\User\UseCase\CreateUser\CreateUserDTO;
use PHPUnit\Framework\TestCase;
use App\Tests\Unit\User\UseCase\RepositoryStub;

class CreateUserTest extends TestCase
{
    public function testCanCreateUser(): void
    {
        $repository = new RepositoryStub();
        $createUser = new CreateUser($repository);

        $createUserDTO = new CreateUserDTO('name', 'test@test.test');
        $userResult = $createUser->create($createUserDTO);
        $userResultData = $userResult->jsonSerialize();

        $this->assertInstanceOf(UserResult::class, $userResult);
        $this->assertEquals('name', $userResultData['name']);
        $this->assertEquals('test@test.test', $userResultData['email']);
        $this->assertNull($userResultData['notes']);
        $this->assertEquals(1, $userResultData['id']);
        $this->assertIsString($userResultData['created']);
        $this->assertNull($userResultData['deleted']);
        $this->assertNotEmpty($repository->find($userResultData['id']));
    }
}