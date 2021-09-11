<?php

declare(strict_types=1);

namespace App\User\Entity;

use App\User\Model\UserUpdatedFields;
use DateTimeImmutable;

class User
{
    private const NOT_UPDATABLE_FIELDS = ['id' => true, 'created' => true];

    private int $id;

    private DateTimeImmutable $created;
    private ?DateTimeImmutable $deleted = null;

    public function __construct(
        private string $name,
        private string $email,
        private ?string $notes = null,
    ) {
        $this->created = new DateTimeImmutable();
    }

    public function update(array $values): UserUpdatedFields
    {
        $updatedFields = new UserUpdatedFields($this->id);

        foreach ($values as $name => $value) {
            if (!$this->isFieldUpdatable($name, $value)) {
                continue;
            }

            $updatedFields->add($name, $this->{$name}, $value);
            $this->{$name} = $value;
        }

        return $updatedFields;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id ?? null,
            'name' => $this->name,
            'email' => $this->email,
            'notes' => $this->notes,
            'created' => $this->created,
            'deleted' => $this->deleted,
        ];
    }

    /**
     * @throws \InvalidArgumentException
     */
    private function isFieldUpdatable(string $fieldName, string|DateTimeImmutable|null $fieldValue): bool
    {
        if (isset(self::NOT_UPDATABLE_FIELDS[$fieldName]) || !property_exists($this, $fieldName)) {
            return false;
        }

        if ($this->{$fieldName} === $fieldValue) {
            return false;
        }

        if ($fieldName === 'deleted' && is_object($fieldValue)) {
            if ($this->deleted && $this->deleted->format(\DATE_ATOM) === $fieldValue->format(\DATE_ATOM)) {
                return false;
            }

            if ($fieldValue < $this->created) {
                throw new \InvalidArgumentException('Field "deleted" can not be lesser than "created"');
            }
        }

        return true;
    }
}