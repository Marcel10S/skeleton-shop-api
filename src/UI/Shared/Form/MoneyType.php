<?php

namespace App\UI\Shared\Form;

use App\Entity\Embeddable\Money;
use Symfony\Component\Form\AbstractType;
use App\Infrastructure\Currency\Provider\CurrencyProvider;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MoneyType extends AbstractType
{
    public function __construct(
        private readonly MoneyTransformer $transformer,
        private readonly CurrencyProvider $currencyProvider,
    ) {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('amount', NumberType::class, [
                'label' => 'Kwota',
                'scale' => 2,
            ])
            ->add('currency', ChoiceType::class, [
                'label' => 'Waluta',
                'choices' => $this->getCurrencyChoices(),
            ]);

        $builder->addModelTransformer($this->transformer);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => null,
            'empty_data' => function ($form) {
                return new Money(
                    $form->get('amount')->getData(),
                    $form->get('currency')->getData()
                );
            }
        ]);
    }

    private function getCurrencyChoices(): array
    {
        $choices = [];

        foreach ($this->currencyProvider->findAll() as $currency) {
            $choices[sprintf('%s (%s)', $currency->getName(), $currency->getCode())] = $currency->getCode();
        }

        return $choices;
    }
}
