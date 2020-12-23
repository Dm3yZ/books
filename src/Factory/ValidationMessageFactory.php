<?php

declare(strict_types=1);

namespace App\Factory;

use Symfony\Component\Validator\ConstraintViolationListInterface;

class ValidationMessageFactory
{
    /**
     * @param ConstraintViolationListInterface $violationList
     * @return array
     */
    public function create(ConstraintViolationListInterface $violationList): array
    {
        $violationHash = [];
        foreach ($violationList as $validationError) {
            $violationHash[$validationError->getPropertyPath()]['errors'][] = $validationError->getMessage();
        }

        return $violationHash;
    }
}
