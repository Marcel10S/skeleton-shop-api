<?php

declare(strict_types=1);

namespace App\UI\Order\Form;

use App\Entity\App\Product;
use App\Infrastructure\Order\DTO\OrderItemDTO;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrderItemType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('product', EntityType::class, [
                'class' => Product::class,
                'choice_label' => fn (Product $product) => sprintf(
                    '%s (%s %s)',
                    $product->getName(),
                    number_format($product->getPrice()->getAmount() / 100, 2, '.', ''),
                    $product->getPrice()->getCurrency(),
                ),
                'label' => 'Produkt',
                'placeholder' => 'Wybierz produkt',
            ])
            ->add('quantity', IntegerType::class, [
                'label' => 'Ilość',
                'attr' => ['min' => 1],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['data_class' => OrderItemDTO::class]);
    }
}
