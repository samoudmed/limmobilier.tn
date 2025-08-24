<?php

namespace App\Form;

use App\Entity\Delegation;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Delegation1Type extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('label')
            ->add('is_published')
            ->add('is_active')
            ->add('created_at')
            ->add('updated_at')
            ->add('gouvernorat')
            ->add('pays')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Delegation::class,
        ]);
    }
}
