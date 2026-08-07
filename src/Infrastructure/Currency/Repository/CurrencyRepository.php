<?php

declare(strict_types=1);

namespace App\Infrastructure\Currency\Repository;

use App\Entity\App\Currency;
use App\Infrastructure\Currency\DTO\CurrencyRateDTO;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/** @extends ServiceEntityRepository<Currency> */
class CurrencyRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Currency::class);
    }

    public function save(Currency $currency): void
    {
        $this->getEntityManager()->persist($currency);
        $this->getEntityManager()->flush();
    }

    public function saveAsDefault(Currency $currency): void
    {
        $entityManager = $this->getEntityManager();

        $entityManager->wrapInTransaction(function () use ($currency, $entityManager): void {
            $newDefaultRate = $currency->getRateToDefaultCurrency();
            $currencies = $this->findAll();

            if ($newDefaultRate !== 100) {
                foreach ($currencies as $configuredCurrency) {
                    $configuredCurrency->setRateToDefaultCurrency((int) round(
                        $configuredCurrency->getRateToDefaultCurrency() * 100 / $newDefaultRate,
                    ));
                    $configuredCurrency->setIsDefault(false);
                }
            }

            $this->createQueryBuilder('c')
                ->update()
                ->set('c.isDefault', ':isDefault')
                ->setParameter('isDefault', false)
                ->getQuery()
                ->execute();

            $currency->setIsDefault(true);
            $currency->setRateToDefaultCurrency(100);
            $entityManager->persist($currency);
            $entityManager->flush();
        });
    }

    /** @param CurrencyRateDTO[] $rates */
    public function saveConfiguration(string $defaultCurrencyCode, array $rates): void
    {
        $ratesByCode = [];
        foreach ($rates as $rate) {
            $ratesByCode[$rate->code] = $rate->rateToDefaultCurrency;
        }

        $entityManager = $this->getEntityManager();
        $entityManager->wrapInTransaction(function () use ($defaultCurrencyCode, $ratesByCode, $entityManager): void {
            $defaultFound = false;

            foreach ($this->findAll() as $currency) {
                if (!array_key_exists($currency->getCode(), $ratesByCode)) {
                    throw new \InvalidArgumentException('A rate must be provided for every currency.');
                }

                $isDefault = $currency->getCode() === $defaultCurrencyCode;
                $currency->setIsDefault($isDefault);
                $currency->setRateToDefaultCurrency($isDefault ? 100 : $ratesByCode[$currency->getCode()]);
                $defaultFound = $defaultFound || $isDefault;
            }

            if (!$defaultFound) {
                throw new \InvalidArgumentException('The selected default currency does not exist.');
            }

            $entityManager->flush();
        });
    }
}
