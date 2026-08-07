<?php

namespace App\UI\Shared\Form;

use App\Entity\Embeddable\Money;
use Symfony\Component\Form\DataTransformerInterface;

class MoneyTransformer implements DataTransformerInterface
{
    public function __construct(private readonly AmountParser $amountParser)
    {
    }

    public function transform($value): array
    {
        if (!$value instanceof Money) {
            return ['amount' => 0, 'currency' => 'PLN'];
        }

        return [
            'amount' => $this->amountParser->formatMinorUnits($value->getAmount()),
            'currency' => $value->getCurrency(),
        ];
    }

    public function reverseTransform($value): Money
    {
        try {
            return new Money(
                $this->amountParser->parseToMinorUnits($value['amount']),
                (string) $value['currency']
            );
        } catch (\InvalidArgumentException $exception) {
            throw new \Symfony\Component\Form\Exception\TransformationFailedException(
                $exception->getMessage(),
                0,
                $exception,
            );
        }
    }
}
