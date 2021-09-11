<?php

declare(strict_types=1);

namespace App\User\Validation;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

class NotWithBannedWordValidator extends ConstraintValidator
{
    public function __construct(private BannedNamesRepository $bannedNamesRepository)
    {
    }

    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof NotWithBannedWord) {
            throw new UnexpectedTypeException($constraint, NotWithBannedWord::class);
        }

        if (null === $value || '' === $value) {
            return;
        }

        if (!is_string($value)) {
            throw new UnexpectedValueException($value, 'string');
        }

        $bannedWordInName = $this->bannedNamesRepository->findBannedWordIn($value);

        if ($bannedWordInName) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ string }}', $bannedWordInName)
                ->addViolation();
        }
    }
}