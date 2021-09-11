<?php

declare(strict_types=1);

namespace App\User\UseCase\UpdateUser;

use DateTimeImmutable;

class UpdateUserDTO
{
    private ?string $name;

    private ?string $email;

    private ?string $notes;

    private string|DateTimeImmutable|null $deleted;

    private array $updatedNames;

    public function __construct(array $fields) {
        foreach ($fields as $name => $value) {
            if (!property_exists($this, $name)) {
                continue;
            }

            $this->{$name} = $value;
            $this->updatedNames[] = $name;
        }
    }

    public function toArray(): array
    {
        if (isset($this->deleted) && is_string($this->deleted)) {
            $this->deleted = new DateTimeImmutable($this->deleted);
        }

        $fields = [];
        foreach ($this->updatedNames as $name) {
            $fields[$name] = $this->{$name};
        }

        return $fields;
    }
}