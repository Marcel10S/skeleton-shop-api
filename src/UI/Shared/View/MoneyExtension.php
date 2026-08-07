<?php

namespace App\UI\Shared\View;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class MoneyExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            new TwigFilter('money_amount', [$this, 'formatMoneyAmount']),
            new TwigFilter('money_currency', [$this, 'formatMoneyCurrency']),
        ];
    }

    public function formatMoneyAmount(int $price): string
    {
        if ($price === 0) {
            return '0.00';
        }

        return number_format($price / 100, 2, '.', '');
    }

    public function formatMoneyCurrency(string $currency): string
    {
        switch ($currency) {
            case 'USD':
                return '$';
            case 'EUR':
                return '€';
            case 'PLN':
                return 'zł';
        };

        return $currency;
    }
}
