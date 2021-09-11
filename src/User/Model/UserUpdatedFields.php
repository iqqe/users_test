<?php

declare(strict_types=1);

namespace App\User\Model;

use DateTimeImmutable;

class UserUpdatedFields
{
    private array $updatedFields = [];

    public function __construct(private int $userId)
    {
    }

    public function add(
        string $fieldName,
        string|DateTimeImmutable|null $oldValue,
        string|DateTimeImmutable|null $newValue
    ): void {
        $this->updatedFields[$fieldName] = [
            'oldValue' => $oldValue,
            'newValue' => $newValue,
        ];
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function toArray(): array
    {
        return $this->updatedFields;
    }

    public function isEmpty(): bool
    {
        return empty($this->updatedFields);
    }
}