<?php

namespace CraftKeen\FCRBundle\Form;

use CraftKeen\FCRBundle\Entity\Manager;
use Doctrine\ORM\EntityRepository;
use Ivory\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use FM\ElfinderBundle\Form\Type\ElFinderType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class BrochureEditType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $language = $options['data']->getLang();

        $builder->add('code', TextType::class, ['disabled' => true])
            ->add('parentName')
            ->add('childName')
            ->add('thumbnail', ElFinderType::class, [
                'label' => 'Thumbnail Image (Square Size: minimum 800x800 pixels and maximum 1600x1600 pixels)',
                'instance' => 'form',
                'enable' => true,
                'required' => false,
                'attr' => [
                    'data-onchange-callback' => 'thumbnailChangeCallback'
                ]
            ])
            ->add('thumbnailAlt', null, [
                'label' => 'Thumbnail Alt (AODA short description. <100 characters)'
            ])
            ->add('managers',
                null, [
                    'label' => ''
                ])
//                EntityType::class, array(
//                'label' => '',
//                'mapped' => false,
//                'data' => $options['data']->getManagers(),
//                'class' => Manager::class,
//                'query_builder' => function (EntityRepository $er) use ($language) {
//                    return $er->createQueryBuilder('m')
//                        ->where('m.lang = :language')
//                        ->setParameter(':language', $language)
//                        ->andWhere('m.status = :status')
//                        ->setParameter(':status', 'live')
//                        ;
//                }
//            ))
            ->add('tenants', null, [
                'label' => ''
            ])
            ->add('details', PropertyDetailsType::class, ['language' => $language])
            ->add('demographic', PropertyDemographicType::class)
            ->add('filters', PropertyFilterType::class)
            ->add('gallery', CollectionType::class, array(
                'entry_type' => PropertyGalleryType::class,
                'entry_options' => [
                    'label' => 'Gallery Item:',
                ],
                //'label' => 'Add, remove values',
                'allow_add' => true,
                'allow_delete' => true,
                'prototype' => true,
                'attr' => array(
                    'class' => 'media-gallery-collection',
                ),
            ))
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
            ->add('isGreen', null, [
                'label' => 'Is Green'
            ])
            ->add('isBoma', CheckboxType::class, [
                'label' => 'Has BOMA',
                'required' => false
            ])
            ->add('isHidden', null, [
                'label' => 'Hide from Property Listing and search engines'
            ])
            ->add('lang')
            ->add('langParent')
            ->add('sortOrder',TextType::class, ['disabled' => true])
            ->add('versionComment', CKEditorType::class, array(
                'config' => array('toolbar' => 'minimal'),
            ))
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'CraftKeen\FCRBundle\Entity\Property',
            'language' => 'CraftKeen\CMS\AdminBundle\Entity\Language'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'craftkeen_fcrbundle_property_edit';
    }


}
