<?php

declare(strict_types=1);

namespace App\User\Validation;

interface BannedDomainsRepository
{
    public function exists(string $name): bool;
}