<?php

namespace App\Tests\Unit\User\Entity;

use App\Tests\Unit\User\UserFactory;
use App\User\Entity\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    public function testCanCreateUserWithoutNotes(): void
    {
        $user = new User('test', 'test@test.test');
        $userData = $user->toArray();

        $this->assertEquals('test', $userData['name']);
        $this->assertEquals('test@test.test', $userData['email']);
        $this->assertNull($userData['notes']);
        $this->assertNull($userData['id']);
        $this->assertInstanceOf(\DateTimeImmutable::class, $userData['created']);
        $this->assertNull($userData['deleted']);
    }

    public function testNotesCanBeSaved(): void
    {
        $user = new User('test', 'test@test.test', 'notes data');
        $userData = $user->toArray();

        $this->assertEquals($userData['notes'], 'notes data');
    }

    public function testUserCanBeUpdatedByValidFieldNames(): void
    {
        $user = $this->createValidUserWithId();

        $deleted = new \DateTimeImmutable('+1 hour');
        $newData = [
            'name' => 'test2',
            'email' => 'test2@test.test',
            'notes' => 'notes data 2',
            'id' => 'should be ignored',
            'created' => 'should be ignored',
            'deleted' => $deleted,
            'random' => 'value',
        ];
        $updatedFields = $user->update($newData);
        $userData = $user->toArray();

        $this->assertEquals($newData['name'], $userData['name']);
        $this->assertEquals($newData['email'], $userData['email']);
        $this->assertEquals($newData['notes'], $userData['notes']);
        $this->assertEquals(1, $userData['id']);
        $this->assertInstanceOf(\DateTimeImmutable::class, $userData['created']);
        $this->assertEquals($deleted, $userData['deleted']);
        $this->assertEquals(4, count($updatedFields->toArray()));
    }

    public function testDeletedCanNotBeLessThanCreated(): void
    {
        $user = $this->createValidUserWithId();

        $this->expectException(\InvalidArgumentException::class);

        $deleted = new \DateTimeImmutable('-1 hour');
        $user->update(['deleted' => $deleted]);
    }

    public function testUpdateWithSameValuesReturnsNoChangedFields(): void
    {
        $user = $this->createValidUserWithId();

        $deletedInitial = new \DateTimeImmutable('+1 hour');
        $initialUpdateData = ['name' => 'test2', 'deleted' => $deletedInitial];
        $user->update($initialUpdateData);

        $deleted = new \DateTimeImmutable($deletedInitial->format(\DATE_ATOM));
        $initialUpdateData = ['name' => 'test2', 'deleted' => $deleted];
        $updatedFields = $user->update($initialUpdateData);

        $this->assertTrue($updatedFields->isEmpty());
    }

    private function createValidUserWithId(): User
    {
        $userData = ['name' => 'test', 'email' => 'test@test.test', 'notes' => 'notes data'];
        $user = UserFactory::createUserWithId(1, $userData);

        return $user;
    }
}