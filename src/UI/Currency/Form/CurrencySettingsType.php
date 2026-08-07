<?php

declare(strict_types=1);

namespace App\UI\Currency\Form;

use App\Infrastructure\Currency\DTO\CurrencySettingsDTO;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CurrencySettingsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('defaultCurrencyCode', ChoiceType::class, [
                'label' => 'Waluta domyślna',
                'choices' => $options['currency_choices'],
                'placeholder' => 'Wybierz walutę domyślną',
                'expanded' => true,
            ])
            ->add('rates', CollectionType::class, [
                'entry_type' => CurrencyRateType::class,
                'entry_options' => ['label' => false],
                'allow_add' => false,
                'allow_delete' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CurrencySettingsDTO::class,
            'currency_choices' => [],
        ]);
        $resolver->setAllowedTypes('currency_choices', 'array');
    }
}
