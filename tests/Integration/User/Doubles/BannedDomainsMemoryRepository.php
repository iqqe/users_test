<?php

namespace App\Tests\Integration\User\Doubles;

use App\User\Validation\BannedDomainsRepository;

class BannedDomainsMemoryRepository implements BannedDomainsRepository
{
    private array $bannedDomains = ['test.cn'];

    public function exists(string $name): bool
    {
        return in_array($name, $this->bannedDomains, true);
    }
}