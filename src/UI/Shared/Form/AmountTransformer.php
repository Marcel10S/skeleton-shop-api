<?php

declare(strict_types=1);

namespace App\UI\Shared\Form;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

final class AmountTransformer implements DataTransformerInterface
{
    public function __construct(private readonly AmountParser $parser)
    {
    }

    public function transform(mixed $value): string
    {
        return $value === null ? '' : $this->parser->formatMinorUnits((int) $value);
    }

    public function reverseTransform(mixed $value): int
    {
        try {
            return $this->parser->parseToMinorUnits($value);
        } catch (\InvalidArgumentException $exception) {
            throw new TransformationFailedException($exception->getMessage(), 0, $exception);
        }
    }
}
