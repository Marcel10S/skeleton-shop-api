<?php

declare(strict_types=1);

namespace App\UI\Order\Form;

use App\Infrastructure\Order\DTO\DeliveryFormDTO;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DeliveryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('courier', ChoiceType::class, [
                'label' => 'Kurier',
                'choices' => ['InPost' => 'inpost', 'DPD' => 'dpd'],
            ])
            ->add('recipientName', TextType::class, ['label' => 'Odbiorca'])
            ->add('addressLine', TextType::class, ['label' => 'Ulica i numer'])
            ->add('postalCode', TextType::class, [
                'label' => 'Kod pocztowy',
                'attr' => ['placeholder' => '00-000'],
            ])
            ->add('city', TextType::class, ['label' => 'Miasto']);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['data_class' => DeliveryFormDTO::class]);
    }
}
