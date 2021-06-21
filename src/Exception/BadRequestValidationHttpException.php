<?php

declare(strict_types=1);

namespace App\Exception;

use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class BadRequestValidationHttpException extends BadRequestHttpException
{
    private ConstraintViolationListInterface $violations;

    public function __construct(ConstraintViolationListInterface $violations)
    {
        parent::__construct();

        $this->violations = $violations;
    }

    public function getErrors(): array
    {
        $errors = [];

        /** @var ConstraintViolation[] $violations */
        $violations = $this->violations;

        foreach ($violations as $violation) {
            $error = null;

            foreach (array_reverse(explode('.', $violation->getPropertyPath())) as $key) {
                $error = [
                    $key => $error ?? (string) $violation->getMessage(),
                ];
            }

            $errors[] = $error;
        }

        return array_merge_recursive(...$errors);
    }
}
