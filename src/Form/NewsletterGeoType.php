<?php

namespace App\Form;

use App\Entity\NewsletterGeo;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class NewsletterGeoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('offre', ChoiceType::class,
                    array('choices' => array(
                    'Vente' => 'Vente',
                    'Location' => 'Location'),                    
                    'label' => 'Offre',
                    'multiple'=>false,
                    'expanded'=>false
                ))
            ->add('kind')
            ->add('ville')
            ->add('gouvernorat')
            ->add('delegation')   
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => NewsletterGeo::class,
        ]);
    }
}
