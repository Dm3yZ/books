<?php

declare(strict_types=1);

namespace App\EventSubscriber;

use App\Dto\Response\Error;
use App\Exception\EntityAlreadyExists;
use App\Exception\EntityNotFoundException;
use App\Exception\ValidationErrorException;
use App\Factory\ValidationMessageFactory;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Class ExceptionSubscriber
 * @package App\EventSubscriber
 */
class ExceptionSubscriber implements EventSubscriberInterface
{
    private ValidationMessageFactory $validationMessageFactory;
    private SerializerInterface $serializer;
    private string $env;

    public function __construct(string $env, ValidationMessageFactory $validationMessageFactory, SerializerInterface $serializer)
    {
        $this->validationMessageFactory = $validationMessageFactory;
        $this->serializer = $serializer;
        $this->env = $env;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::EXCEPTION => 'onKernelException',
        ];
    }

    public function onKernelException(ExceptionEvent $event): void
    {
        $event->allowCustomResponseCode();
        $throwable = $event->getThrowable();

        $message = $throwable->getMessage();
        $description = null;
        $trace = null;
        $errorCode = null;

        switch (true) {
            case $throwable instanceof ValidationErrorException:
                $statusCode = $throwable->getStatusCode();
                $description = $this->validationMessageFactory->create($throwable->getViolationList());
                break;
            case $throwable instanceof HttpExceptionInterface:
                $statusCode = $throwable->getStatusCode();
                break;
            case $throwable instanceof EntityNotFoundException:
                $statusCode = Response::HTTP_NOT_FOUND;
                break;
            case $throwable instanceof EntityAlreadyExists:
                $statusCode = Response::HTTP_CONFLICT;
                break;
            default:
                $statusCode = Response::HTTP_INTERNAL_SERVER_ERROR;
                $message = $this->env !== 'prod' ? $message : null;
                $trace = $this->env !== 'prod' ? $throwable->getTraceAsString() : null;
        }

        $errorResponse = new Error($message, $description, $trace, $errorCode);
        $responseContent = $this->serializer->serialize($errorResponse, 'json', [AbstractObjectNormalizer::SKIP_NULL_VALUES => true]);
        $response = JsonResponse::fromJsonString($responseContent, $statusCode);

        $event->setResponse($response);
    }
}
