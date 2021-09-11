<?php

declare(strict_types=1);

namespace App\User\Validation;

use Symfony\Component\Validator\Constraint;

#[\Attribute]
class NotWithBannedWord extends Constraint
{
    public $message = 'Name can not include the word "{{ string }}". Please choose another name.';
}