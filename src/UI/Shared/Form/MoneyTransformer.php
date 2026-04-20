<?php

namespace App\UI\Shared\Form;

use App\Entity\Embeddable\Money;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MoneyTransformer implements DataTransformerInterface
{
    public function transform($value): array
    {
        if (!$value instanceof Money) {
            return ['amount' => 0, 'currency' => 'PLN'];
        }

        return [
            'amount' => $value->getAmount(),
            'currency' => $value->getCurrency(),
        ];
    }

    public function reverseTransform($value): Money
    {
        return new Money(
            (int) $value['amount'],
            (string) $value['currency']
        );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Money::class,
        ]);
    }
}
