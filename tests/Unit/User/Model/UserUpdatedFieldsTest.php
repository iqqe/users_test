<?php

namespace App\Tests\Unit\User\Model;

use App\User\Model\UserUpdatedFields;
use PHPUnit\Framework\TestCase;

class UserUpdatedFieldsTest extends TestCase
{
    public function testCanGetUserId(): void
    {
        $updatedFields = new UserUpdatedFields(1);

        $this->assertEquals(1, $updatedFields->getUserId());
    }

    public function testEmptyObject(): void
    {
        $updatedFields = new UserUpdatedFields(1);

        $this->assertTrue($updatedFields->isEmpty());
        $this->assertEmpty($updatedFields->toArray());
    }

    public function testCanAddValues(): void
    {
        $updatedFields = new UserUpdatedFields(1);

        $date = new \DateTimeImmutable();
        $updatedFields->add('first', null, 'string');
        $updatedFields->add('second', 'string', null);
        $updatedFields->add('third', $date, null);
        $updatedFields->add('fourth', null, $date);

        $this->assertEquals(
            [
                'first' => ['oldValue' => null, 'newValue' => 'string'],
                'second' => ['oldValue' => 'string', 'newValue' => null],
                'third' => ['oldValue' => $date, 'newValue' => null],
                'fourth' => ['oldValue' => null, 'newValue' => $date],
            ],
            $updatedFields->toArray()
        );
    }
}