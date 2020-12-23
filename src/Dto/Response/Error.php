<?php

declare(strict_types=1);

namespace App\Dto\Response;

class Error
{
    public ?string $message;
    public ?array $description;
    public ?string $trace;
    public ?int $errorCode;

    /**
     * Error constructor.
     * @param string|null $message
     * @param array|null $description
     * @param string|null $trace
     * @param int|null $errorCode
     */
    public function __construct(?string $message, ?array $description, ?string $trace, ?int $errorCode)
    {
        $this->message = $message;
        $this->description = $description;
        $this->trace = $trace;
        $this->errorCode = $errorCode;
    }
}
