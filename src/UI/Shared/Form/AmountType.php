<?php

declare(strict_types=1);

namespace App\UI\Shared\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;

class AmountType extends AbstractType
{
    public function __construct(private readonly AmountTransformer $transformer)
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->addModelTransformer($this->transformer);
    }

    public function getParent(): string
    {
        return NumberType::class;
    }
}
