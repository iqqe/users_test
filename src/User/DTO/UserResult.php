<?php

declare(strict_types=1);

namespace App\User\DTO;

use DateTimeImmutable;

class UserResult implements \JsonSerializable
{
    public function __construct(
        private int $id,
        private string $name,
        private string $email,
        private DateTimeImmutable $created,
        private ?string $notes,
        private ?DateTimeImmutable $deleted,
    ) {
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'created' => $this->created->format(\DateTime::ATOM),
            'notes' => $this->notes,
            'deleted' => $this->deleted?->format(\DateTime::ATOM),
        ];
    }
}