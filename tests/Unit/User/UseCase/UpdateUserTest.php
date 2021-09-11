<?php

namespace App\Tests\Unit\User\UseCase;

use App\Tests\Unit\User\UserFactory;
use App\User\DTO\UserResult;
use App\User\Event\UserUpdatedEvent;
use App\User\Model\UserUpdatedFields;
use App\User\UseCase\UpdateUser\UpdateUser;
use App\User\UseCase\UpdateUser\UpdateUserDTO;
use App\User\UseCase\UserNotFoundException;
use PHPUnit\Framework\TestCase;
use App\Tests\Unit\User\UseCase\RepositoryStub;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class UpdateUserTest extends TestCase
{
    public function testCanUpdateExistingUser(): void
    {
        $user = UserFactory::createUserWithId(1, ['name' => 'test', 'email' => 'test@test.test']);
        $repository = new RepositoryStub([$user]);
        $dispatcherStub = $this->createStub(EventDispatcherInterface::class);
        $updateUser = new UpdateUser($repository, $dispatcherStub);

        $newData = [
            'name' => 'test2',
            'email' => 'test2@test.test',
            'notes' => 'notes',
            'deleted' => '3333-01-01T00:00:00+00:00'
        ];
        $createUserDTO = new UpdateUserDTO($newData);
        $userResult = $updateUser->update(1, $createUserDTO);
        $userResultData = $userResult->jsonSerialize();

        $this->assertInstanceOf(UserResult::class, $userResult);
        $this->assertEquals($newData['name'], $userResultData['name']);
        $this->assertEquals($newData['email'], $userResultData['email']);
        $this->assertEquals($newData['notes'], $userResultData['notes']);
        $this->assertEquals(1, $userResultData['id']);
        $this->assertNotEmpty($userResultData['created']);
        $this->assertEquals($newData['deleted'], $userResultData['deleted']);
        $this->assertNotEmpty($repository->find($userResultData['id']));
    }

    public function testNotFoundIsThrownIfNoExistingUser(): void
    {
        $repository = new RepositoryStub();
        $dispatcherStub = $this->createStub(EventDispatcherInterface::class);
        $updateUser = new UpdateUser($repository, $dispatcherStub);

        $this->expectException(UserNotFoundException::class);

        $createUserDTO = new UpdateUserDTO([]);
        $updateUser->update(1, $createUserDTO);
    }

    public function testEventIsDispatchedOnUserUpdate(): void
    {
        $user = UserFactory::createUserWithId(1, ['name' => 'test', 'email' => 'test@test.test']);
        $repository = new RepositoryStub([$user]);

        $updatedFields = new UserUpdatedFields(1);
        $updatedFields->add('name', 'test', 'test2');
        $dispatcherStub = $this->createStub(EventDispatcherInterface::class);
        $dispatcherStub
            ->expects($this->once())
            ->method('dispatch')
            ->with(new UserUpdatedEvent($updatedFields));
        $updateUser = new UpdateUser($repository, $dispatcherStub);

        $createUserDTO = new UpdateUserDTO(['name' => 'test2']);
        $updateUser->update(1, $createUserDTO);
    }

    public function testEventIsNotDispatchedIfUpdateHasNoChanges(): void
    {
        $user = UserFactory::createUserWithId(1, ['name' => 'test', 'email' => 'test@test.test']);
        $repository = new RepositoryStub([$user]);

        $updatedFields = new UserUpdatedFields(1);
        $updatedFields->add('name', 'test', 'test');
        $dispatcherStub = $this->createStub(EventDispatcherInterface::class);
        $dispatcherStub->expects($this->exactly(0))->method('dispatch');
        $updateUser = new UpdateUser($repository, $dispatcherStub);

        $createUserDTO = new UpdateUserDTO(['name' => 'test']);
        $updateUser->update(1, $createUserDTO);
    }
}