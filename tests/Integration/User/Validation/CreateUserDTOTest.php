<?php

namespace App\Tests\Integration\User\Validation;

use App\User\UseCase\CreateUser\CreateUserDTO;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CreateUserDTOTest extends KernelTestCase
{
    protected function setUp(): void
    {
        self::bootKernel();
    }

    /** @dataProvider getDataForDTO */
    public function testValidationErrors(array $createUserData, int $expectedErrorsCount): void
    {
        $validator = $this->getContainer()->get(ValidatorInterface::class);

        $createUserDTO = new CreateUserDTO(...$createUserData);
        $errors = $validator->validate($createUserDTO);

        $this->assertCount($expectedErrorsCount, $errors);
    }

    public function getDataForDTO(): array
    {
        return [
            'valid data' => [['name' => 'testtest', 'email' => 'test@test.test'], 0],
            'valid data with notes' => [['name' => 'testtest', 'email' => 'test@test.test', 'notes' => 'text'], 0],
            'short name' => [['name' => 'test', 'email' => 'test@test.test'], 1],
            'invalid letters in the name' => [['name' => 'test------', 'email' => 'test@test.test'], 1],
            'banned word in the name' => [['name' => 'testbanned', 'email' => 'test@test.test'], 1],
            'email is from a banned domain' => [['name' => 'testtest', 'email' => 'test@test.cn'], 1],
            'short name and invalid email' => [['name' => 'test', 'email' => 'test'], 2],
        ];
    }
}