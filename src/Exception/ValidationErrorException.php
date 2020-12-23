<?php

declare(strict_types=1);

namespace App\Exception;

use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Throwable;

class ValidationErrorException extends BadRequestHttpException
{
    /**
     * @var ConstraintViolationListInterface
     */
    private ConstraintViolationListInterface $violationList;

    public function __construct(
        ConstraintViolationListInterface $violationList,
        string $message = 'Bad request given',
        Throwable $previous = null,
        int $code = 0,
        array $headers = []
    ) {
        parent::__construct($message, $previous, $code, $headers);
        $this->violationList = $violationList;
    }

    /**
     * @return ConstraintViolationListInterface
     */
    public function getViolationList(): ConstraintViolationListInterface
    {
        return $this->violationList;
    }
}
