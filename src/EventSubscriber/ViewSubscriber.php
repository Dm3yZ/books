<?php

declare(strict_types=1);

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

class ViewSubscriber implements EventSubscriberInterface
{
    public SerializerInterface $serializer;

    /**
     * ViewSubscriber constructor.
     * @param SerializerInterface $serializer
     */
    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::VIEW => 'onKernelView',
        ];
    }

    /**
     * @param ViewEvent $event
     */
    public function onKernelView(ViewEvent $event): void
    {
        $result = $event->getControllerResult();
        $statusCode = Response::HTTP_OK;
        if (null  === $result) {
            $statusCode = Response::HTTP_NO_CONTENT;
        }

        $responseContent = $this->serializer->serialize($result, 'json');
        $response = JsonResponse::fromJsonString($responseContent, $statusCode, [AbstractObjectNormalizer::SKIP_NULL_VALUES => true]);

        $event->setResponse($response);
    }
}
