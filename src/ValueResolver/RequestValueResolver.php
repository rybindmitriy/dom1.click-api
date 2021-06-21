<?php

declare(strict_types=1);

namespace App\ValueResolver;

use App\Dto\Request\RequestInterface;
use App\Exception\BadRequestValidationHttpException;
use JMS\Serializer\ArrayTransformerInterface;
use LogicException;
use ReflectionClass;
use ReflectionException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Throwable;

final class RequestValueResolver implements ArgumentValueResolverInterface
{
    public function __construct(
        private ArrayTransformerInterface $arrayTransformer,
        private ValidatorInterface $validator
    ) {
    }

    /**
     * @throws BadRequestHttpException
     * @throws BadRequestValidationHttpException
     * @throws LogicException
     */
    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        /* @psalm-var class-string */
        $argumentType = $argument->getType() ?? '';

        try {
            /** @var array */
            $data = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);

            /** @var RequestInterface */
            $dto = $this->arrayTransformer->fromArray($data, $argumentType);
        } catch (Throwable) {
            throw new BadRequestHttpException();
        }

        $violations = $this->validator->validate($dto);

        if (0 < $violations->count()) {
            throw new BadRequestValidationHttpException($violations);
        }

        yield $dto;
    }

    public function supports(Request $request, ArgumentMetadata $argument): bool
    {
        /** @psalm-var class-string|null */
        $argumentType = $argument->getType();

        if (null === $argumentType) {
            return false;
        }

        try {
            return (new ReflectionClass($argumentType))->implementsInterface(RequestInterface::class);
        } catch (ReflectionException) {
        }

        return false;
    }
}
