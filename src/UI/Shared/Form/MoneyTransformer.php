<?php

namespace App\UI\Shared\Form;

use App\Entity\Embeddable\Money;
use Symfony\Component\Form\DataTransformerInterface;

class MoneyTransformer implements DataTransformerInterface
{
    public function transform($value): array
    {
        if (!$value instanceof Money) {
            return ['amount' => 0, 'currency' => 'PLN'];
        }

        return [
            'amount' => number_format($value->getAmount() / 100, 2, '.', ''),
            'currency' => $value->getCurrency(),
        ];
    }

    public function reverseTransform($value): Money
    {
        return new Money(
            (int) round(((float) $value['amount']) * 100),
            (string) $value['currency']
        );
    }
}
