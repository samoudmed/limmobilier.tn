<?php

namespace App\Form;

use App\Entity\Meta;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class MetaType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options): void {
        $builder
                ->add('title')
                ->add('description')
                ->add('entity', ChoiceType::class,
                        array('choices' => array(
                                'annonce' => 'annonce',
                            ),
                            'label' => 'Offre',
                            'multiple' => false,
                            'expanded' => false
                ))
                ->add('idEntity')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void {
        $resolver->setDefaults([
            'data_class' => Meta::class,
        ]);
    }

}
