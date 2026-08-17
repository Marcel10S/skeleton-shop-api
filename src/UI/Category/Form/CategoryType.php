<?php

namespace App\UI\Category\Form;

use App\Entity\App\Category;
use App\Infrastructure\Category\DTO\CategoryFormDTO;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CategoryType extends AbstractType
{
    public function buildForm(
        FormBuilderInterface $builder,
        array $options
    ): void {
        $builder
            ->add('name', TextType::class, ['label' => 'Nazwa'])
            ->add('description', TextareaType::class, [
                'label' => 'Opis',
                'required' => false,
            ])
            ->add('parent', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'name',
                'label' => 'Kategoria nadrzędna',
                'required' => false,
                'placeholder' => 'Kategoria główna',
            ]);
    }

    public function configureOptions(
        OptionsResolver $resolver
    ): void {
        $resolver->setDefaults([
            'data_class' => CategoryFormDTO::class,
        ]);
    }
}
