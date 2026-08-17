<?php

declare(strict_types=1);

namespace App\UI\Currency\Form;

use App\Infrastructure\Currency\DTO\CurrencyFormDTO;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CurrencyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('code', TextType::class, [
                'label' => 'Kod',
                'attr' => ['maxlength' => 3, 'style' => 'text-transform: uppercase'],
            ])
            ->add('name', TextType::class, ['label' => 'Nazwa']);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CurrencyFormDTO::class,
        ]);
    }
}
