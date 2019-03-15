<?php

namespace CraftKeen\FCRBundle\Form;

use CraftKeen\CMS\AdminBundle\Entity\Site;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use FM\ElfinderBundle\Form\Type\ElFinderType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class PropertyWizardType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('code', null, ['required' => true])
                ->add('parentName', null, ['required' => true])
                ->add('childName', null, ['required' => true])
                ->add('thumbnail', ElFinderType::class, [
                    'label' => 'Thumbnail Image (Square Size: minimum 800x800 pixels and maximum 1600x1600 pixels)',
                    'instance' => 'form',
                    'enable' => true,
                    'required' => false
                ])
                ->add('thumbnailAlt', null, [
                    'label' => 'Thumbnail Alt (AODA short description)',
                    'required' => false
                ])
                ->add('managers', null, [
                    'label' => 'Leasing & PM Contacts (maximum 3 can be selected)',
                ])
                ->add('tenants', null, [
                    'label' => 'Tenants Logos (minimum 3 must be selected)',
                    'required' => false
                ])
                ->add('isGreen', null, [
                    'label' => 'Is Green',
                ])
                ->add('isBoma', null, [
                    'label' => 'Has BOMA',
                ])
                ->add('vacancyList', CollectionType::class, array(
                    'entry_type' => PropertyVacancyType::class,
                    'entry_options' => [
                        'label' => ' ',
                    ],
    //                'label' => 'Add, remove values',
                    'allow_add' => true,
                    'allow_delete' => true,
                    'prototype' => true,
                    'attr' => array(
                        'class' => 'vacancyList-collection',
                    ),
                ))
                ->add('isHidden')
                ->add('lang')
                ->add('site', EntityType::class, array(
                    'class' => Site::class,
                    'query_builder' => function(EntityRepository $er) {
                        return $er->createQueryBuilder('p');
                    }
                ))
                ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'CraftKeen\FCRBundle\Entity\Property'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'craftkeen_fcrbundle_property';
    }


}
