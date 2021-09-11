<?php

declare(strict_types=1);

namespace App\User\Validation;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

class NotWithBannedEmailDomainValidator extends ConstraintValidator
{
    public function __construct(private BannedDomainsRepository $bannedDomainsRepository)
    {
    }

    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof NotWithBannedEmailDomain) {
            throw new UnexpectedTypeException($constraint, NotWithBannedEmailDomain::class);
        }

        if (null === $value || '' === $value) {
            return;
        }

        if (!is_string($value)) {
            throw new UnexpectedValueException($value, 'string');
        }

        $emailParts = explode('@', $value);
        $domain = end($emailParts);

        if ($this->bannedDomainsRepository->exists($domain)) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ string }}', $domain)
                ->addViolation();
        }
    }
}