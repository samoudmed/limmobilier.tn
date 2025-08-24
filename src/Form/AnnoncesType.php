<?php

namespace App\Form;

use App\Entity\Annonces;
use App\Entity\Delegation;
use App\Entity\Gouvernorat;
use App\Entity\Villes;
use Doctrine\ORM\EntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use FOS\CKEditorBundle\Form\Type\CKEditorType;

class AnnoncesType extends AbstractType
{
    private $doctrine;

    public function __construct(ManagerRegistry $doctrine)
    {
        $this->doctrine = $doctrine;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('label')
            ->add('surface')
            ->add('adresse', TextType::class, [
                'required' => false
            ])
            ->add('localisationMap')
            ->add('description', CKEditorType::class, [
                'config_name' => 'my_config',
                'required' => true
            ])
            ->add('offre', ChoiceType::class, [
                'choices' => [
                    'Vente' => 'Vente',
                    'Location' => 'Location'
                ],
                'label' => 'Offre',
                'multiple' => false,
                'expanded' => false,
                'placeholder' => 'Type de transaction'
            ])
            ->add('prix')
            ->add('instalment', ChoiceType::class, [
                'choices' => [
                    '' => '0',
                    'Jour' => 'j',
                    'Semaine' => 's',
                    'Mois' => 'm',
                    'Année' => 'a'
                ],
                'label' => 'Par',
                'multiple' => false,
                'expanded' => false
            ])
            ->add('anneeConstruction', ChoiceType::class, [
                'choices' => $this->getYears(1900),
                'label' => 'Année de construction',
                'multiple' => false,
                'expanded' => false,
                'required' => false,
                'placeholder' => 'Année de construction'
            ])
            ->add('orientation', ChoiceType::class, [
                'choices' => [
                    'Pas d\'informations' => '0',
                    'Nord' => '1',
                    'Nord-Est' => '2',
                    'Est' => '3',
                    'Sud-Est' => '4',
                    'Sud' => '5',
                    'Sud-Ouest' => '6',
                    'Ouest' => '7',
                    'Nord-Ouest' => '8'
                ],
                'label' => 'Orientation',
                'multiple' => false,
                'expanded' => false
            ])
            ->add('etage', ChoiceType::class, [
                'choices' => [
                    'RDC' => '0',
                    '1er étage' => '1',
                    '2ème étage' => '2',
                    '3ème étage' => '3',
                    '4ème étage' => '4',
                    '5ème étage' => '5',
                    '6ème étage' => '6',
                    '7ème étage' => '7',
                    '8ème étage' => '8',
                    '9ème étage' => '9',
                    '10ème étage' => '10'
                ],
                'label' => 'Etage',
                'multiple' => false,
                'expanded' => false,
                'required' => false
            ])
            ->add('climatiseur')
            ->add('pieces')
            ->add('piscine')
            ->add('parking')
            ->add('chauffage')
            ->add('capacite')
            ->add('internet')
            ->add('meuble')
            ->add('salleBain')
            ->add('securite')
            ->add('ascenseur')
            ->add('cheminee')
            ->add('cuisineEquipe')
            ->add('jacuzzi')
            ->add('jardin')
            ->add('electricite')
            ->add('gaz')
            ->add('telephone')
            ->add('eau')
            ->add('assainissement')
            ->add('permisConstruction')
            ->add('vue')
            ->add('disponibilite', DateType::class, [
                'widget' => 'single_text',
                'format' => 'yyyy-MM-dd',
            ])
            ->add('pays')
            ->add('kind', EntityType::class, [
                'class' => \App\Entity\Kind::class,
                'choice_label' => 'label',
                'multiple' => false,
                'required' => true,
                'placeholder' => 'Type de bien',
                'label' => 'Type de bien',
            ])
            ->add('expired_at', ChoiceType::class, [
                'choices' => [
                    'Deux semaines' => '15',
                    'Un mois' => '30',
                    'Trois mois' => '90'
                ],
                'empty_data' => '30',
                'label' => 'Date d\'expiration',
                'multiple' => false,
                'expanded' => false,
                'mapped' => false
            ])
            // Place the parent fields at the end of the form builder
            // to ensure they are available to the listeners.
            ->add('gouvernorat', EntityType::class, [
                'class' => Gouvernorat::class,
                'choice_label' => 'label',
                'multiple' => false,
                'required' => true,
                'placeholder' => 'Gouvernorat',
            ])
            ->add('delegation', EntityType::class, [
                'class' => Delegation::class,
                'choice_label' => 'label',
                'multiple' => false,
                'required' => false,
                'placeholder' => 'Délégation',
                'choices' => [],
            ])
            ->add('ville', EntityType::class, [
                'class' => Villes::class,
                'choice_label' => 'label',
                'multiple' => false,
                'required' => false,
                'placeholder' => 'Ville',
                'choices' => [],
            ]);
        
        // Form event listeners
        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function (FormEvent $event) {
                $form = $event->getForm();
                $data = $event->getData();

                if ($data && $data->getGouvernorat()) {
                    $this->addDelegationField($form, $data->getGouvernorat());
                    if ($data->getDelegation()) {
                        $this->addVilleField($form, $data->getDelegation());
                    }
                }
            }
        );

        $builder->addEventListener(
            FormEvents::PRE_SUBMIT,
            function (FormEvent $event) {
                $form = $event->getForm();
                $data = $event->getData();

                $gouvernoratId = $data['gouvernorat'] ?? null;
                if ($gouvernoratId) {
                    $gouvernorat = $this->doctrine->getRepository(Gouvernorat::class)->find($gouvernoratId);
                    $this->addDelegationField($form, $gouvernorat);

                    $delegationId = $data['delegation'] ?? null;
                    if ($delegationId) {
                        $delegation = $this->doctrine->getRepository(Delegation::class)->find($delegationId);
                        $this->addVilleField($form, $delegation);
                    }
                }
            }
        );
    }
    
    // The helper methods below are correct as you provided them
    private function addDelegationField(FormInterface $form, ?Gouvernorat $gouvernorat)
    {
        $form->add('delegation', EntityType::class, [
            'class' => Delegation::class,
            'choice_label' => 'label',
            'multiple' => false,
            'required' => false,
            'placeholder' => 'Délégation',
            'query_builder' => function (EntityRepository $er) use ($gouvernorat) {
                if (!$gouvernorat) return $er->createQueryBuilder('d')->where('1 = 0'); // No results if no gouvernorat
                return $er->createQueryBuilder('d')
                    ->where('d.gouvernorat = :gouvernorat')
                    ->setParameter('gouvernorat', $gouvernorat)
                    ->orderBy('d.label', 'ASC');
            },
        ]);
    }
    
    private function addVilleField(FormInterface $form, ?Delegation $delegation)
    {
        $form->add('ville', EntityType::class, [
            'class' => Villes::class,
            'choice_label' => 'label',
            'multiple' => false,
            'required' => false,
            'placeholder' => 'Ville',
            'query_builder' => function (EntityRepository $er) use ($delegation) {
                if (!$delegation) return $er->createQueryBuilder('v')->where('1 = 0'); // No results if no delegation
                return $er->createQueryBuilder('v')
                    ->where('v.delegation = :delegation')
                    ->setParameter('delegation', $delegation)
                    ->orderBy('v.label', 'ASC');
            },
        ]);
    }
    // ... rest of your class
    
    private function getYears($min, $max = 'current')
    {
        $years = range($min, ($max === 'current' ? date('Y') : $max));

        return array_combine($years, $years);
    }
}