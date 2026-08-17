<?php

declare(strict_types=1);

namespace App\UI\Shared\Form;

final class AmountParser
{
    public function parseToMinorUnits(mixed $value): int
    {
        $value = str_replace(',', '.', trim((string) $value));

        if (!preg_match('/^(\d+)(?:\.(\d{1,2}))?$/', $value, $matches)) {
            throw new \InvalidArgumentException('Podaj kwotę z maksymalnie dwoma miejscami po przecinku.');
        }

        return ((int) $matches[1] * 100) + (int) str_pad($matches[2] ?? '', 2, '0');
    }

    public function formatMinorUnits(int $amount): string
    {
        return number_format($amount / 100, 2, '.', '');
    }
}
