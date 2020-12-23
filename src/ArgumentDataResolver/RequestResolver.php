<?php

declare(strict_types=1);

namespace App\ArgumentDataResolver;

use App\Exception\ValidationErrorException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\HttpKernel\Exception\UnsupportedMediaTypeHttpException;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use function count;

class RequestResolver implements ArgumentValueResolverInterface
{
    private SerializerInterface $serializer;
    private ValidatorInterface $validator;

    public function __construct(SerializerInterface $serializer, ValidatorInterface $validator)
    {
        $this->serializer = $serializer;
        $this->validator = $validator;
    }

    public function supports(Request $request, ArgumentMetadata $argument): bool
    {
        return class_exists($argument->getType());
    }

    public function resolve(Request $request, ArgumentMetadata $argument)
    {
        $contentTypes = $request->getAcceptableContentTypes();
        if ($this->isUnsupportedTypeGiven($contentTypes)) {
            throw new UnsupportedMediaTypeHttpException();
        }

        $argumentObj = $this->serializer->deserialize($request->getContent(), $argument->getType(), 'json');
        $violationsList = $this->validator->validate($argumentObj);

        if (count($violationsList) > 0) {
            throw new ValidationErrorException($violationsList);
        }

        yield $argumentObj;
    }

    /**
     * @param array<string> $contentTypes
     * @return bool
     */
    private function isUnsupportedTypeGiven(array $contentTypes): bool
    {
        return ! empty($contentTypes) && ! in_array('application/json', $contentTypes, false);
    }
}
