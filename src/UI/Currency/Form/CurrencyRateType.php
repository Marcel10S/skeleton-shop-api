<?php

declare(strict_types=1);

namespace App\UI\Currency\Form;

use App\Infrastructure\Currency\DTO\CurrencyRateDTO;
use App\UI\Shared\Form\AmountType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CurrencyRateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('code', TextType::class, ['disabled' => true])
            ->add('name', TextType::class, ['disabled' => true])
            ->add('rateToDefaultCurrency', AmountType::class, [
                'label' => 'Kurs do waluty domyślnej',
                'scale' => 2,
                'attr' => ['min' => 0.01, 'step' => 0.01],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['data_class' => CurrencyRateDTO::class]);
    }
}
