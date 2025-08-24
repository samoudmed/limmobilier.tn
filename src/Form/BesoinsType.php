<?php

namespace App\Form;

use App\Entity\Besoins;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BesoinsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('type_besoin')
            ->add('commentaire')
            ->add('prix_max')
            ->add('disponibilite')
            ->add('exprired_at')
            ->add('created_at')
            ->add('updated_at')
            ->add('type_bien')
            ->add('ville')
            ->add('user')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Besoins::class,
        ]);
    }
}
