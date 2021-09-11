<?php

declare(strict_types=1);

namespace App\User\Validation;

use Symfony\Component\Validator\Constraint;

#[\Attribute]
class NotWithBannedEmailDomain extends Constraint
{
    public $message = 'Email domain "{{ string }}" is prohibited. Please choose another email.';
}