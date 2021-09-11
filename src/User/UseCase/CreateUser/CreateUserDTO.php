<?php

declare(strict_types=1);

namespace App\User\UseCase\CreateUser;

class CreateUserDTO
{
    public function __construct(
        private string $name,
        private string $email,
        private ?string $notes = null,
    ) {
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'email' => $this->email,
            'notes' => $this->notes,
        ];
    }
}