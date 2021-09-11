<?php

declare(strict_types=1);

namespace App\User\Validation;

interface BannedNamesRepository
{
    public function findBannedWordIn(string $name): ?string;
}