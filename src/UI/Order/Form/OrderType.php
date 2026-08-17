<?php

declare(strict_types=1);

namespace App\UI\Order\Form;

use App\Infrastructure\Order\DTO\OrderFormDTO;
use App\Infrastructure\PaymentMethod\Provider\PaymentMethodProvider;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrderType extends AbstractType
{
    public function __construct(private readonly PaymentMethodProvider $paymentMethodProvider)
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('items', CollectionType::class, [
                'entry_type' => OrderItemType::class,
                'entry_options' => ['label' => false],
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'prototype' => true,
            ])
            ->add('delivery', DeliveryType::class, ['label' => false])
            ->add('paymentMethod', ChoiceType::class, [
                'label' => 'Metoda płatności',
                'placeholder' => 'Wybierz metodę płatności',
                'choices' => $this->paymentMethodProvider->findAll(),
                'choice_label' => static fn ($paymentMethod): string => $paymentMethod->getName(),
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['data_class' => OrderFormDTO::class]);
    }
}
