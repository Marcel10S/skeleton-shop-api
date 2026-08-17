<?php

namespace App\UI\Product\Form;

use App\Entity\App\Category;
use App\Infrastructure\Product\DTO\ProductFormDTO;
use App\UI\Shared\Form\MoneyType;
use Symfony\Component\Form\AbstractType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'name',
                'label' => 'Kategoria',
                'placeholder' => 'Wybierz kategorię',
            ])
            ->add('name', TextType::class, ['label' => 'Nazwa'])
            ->add('description', TextareaType::class, [
                'label' => 'Opis',
                'required' => false,
            ])
            ->add('stock', IntegerType::class, ['label' => 'Stan magazynowy'])
            ->add('isActive', CheckboxType::class, [
                'label' => 'Produkt aktywny',
                'required' => false,
            ])
            ->add('price', MoneyType::class);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ProductFormDTO::class,
        ]);
    }
}
