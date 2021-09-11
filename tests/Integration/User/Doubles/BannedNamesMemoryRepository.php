<?php

namespace App\Tests\Integration\User\Doubles;

use App\User\Validation\BannedNamesRepository;

class BannedNamesMemoryRepository implements BannedNamesRepository
{
    private array $bannedWords = ['banned'];

    public function findBannedWordIn(string $name): ?string
    {
        foreach ($this->bannedWords as $bannedWord) {
            if (mb_strpos($name, $bannedWord) !== false) {
                return $bannedWord;
            }
        }

        return null;
    }
}