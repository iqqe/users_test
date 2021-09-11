<?php

namespace App\Tests\Integration\User\Validation;

use App\User\UseCase\UpdateUser\UpdateUserDTO;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UpdateUserDTOTest extends KernelTestCase
{
    protected function setUp(): void
    {
        self::bootKernel();
    }

    /**
     * @dataProvider getValidDataForDTO
     * @dataProvider getInvalidDataForDTO
     */
    public function testValidationErrors(array $updateUserData, int $expectedErrorsCount): void
    {
        $validator = $this->getContainer()->get(ValidatorInterface::class);

        $updateUserDTO = new UpdateUserDTO($updateUserData);
        $errors = $validator->validate($updateUserDTO);

        $this->assertCount($expectedErrorsCount, $errors);
    }

    public function getValidDataForDTO(): array
    {
        return [
            'only name is updated' => [['name' => 'testtest'], 0],
            'only email is updated' => [['email' => 'test@test.test'], 0],
            'only deleted is updated' => [['deleted' => '2150-01-01T00:00:00+00:00'], 0],
            'valid name and email' => [['name' => 'testtest', 'email' => 'test@test.test'], 0],
            'valid data with notes' => [['name' => 'testtest', 'email' => 'test@test.test', 'notes' => 'text'], 0],
            'valid data with deleted' => [
                [
                    'name' => 'testtest',
                    'email' => 'test@test.test',
                    'notes' => 'text',
                    'deleted' => '2150-01-01T00:00:00+00:00'
                ],
                0
            ],
        ];
    }

    public function getInvalidDataForDTO(): array
    {
        return [
            'short name' => [['name' => 'test', 'email' => 'test@test.test'], 1],
            'invalid letters in the name' => [['name' => 'test------', 'email' => 'test@test.test'], 1],
            'banned word in the name' => [['name' => 'testbanned', 'email' => 'test@test.test'], 1],
            'incorrect deleted' => [['deleted' => 'testtest'], 1],
            'email is from a banned domain' => [['name' => 'testtest', 'email' => 'test@test.cn'], 1],
            'short name and invalid email' => [['name' => 'test', 'email' => 'test'], 2],
        ];
    }
}