<?php

namespace CraftKeen\FCRBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Ivory\CKEditorBundle\Form\Type\CKEditorType;
use FM\ElfinderBundle\Form\Type\ElFinderType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;
use CraftKeen\FCRBundle\Entity\RetailArtCategory;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class RetailArtType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $language = $options['language'];

        $builder->add('categories', EntityType::class, array(
                    'mapped' => false,
					'class' => RetailArtCategory::class,
					'query_builder' => function(EntityRepository $er) use ($language) {
						return $er->createQueryBuilder('p')
								->where('p.lang = :language')
								->setParameter(':language', $language);
					}
				))
                ->add('head')
                ->add('slug')
                ->add('title')
                ->add('text', CKEditorType::class)
                ->add('short', CKEditorType::class, array(
					'config' => array('toolbar' => 'minimal'),
				))
                ->add('image', ElFinderType::class, ['instance' => 'form', 'enable' => true])
                ->add('gallery', CollectionType::class, array(
                    'entry_type' => RetailArtGalleryType::class,
                    'entry_options' => [
                        'label' => 'Gallery Item:',
                    ],
                    'allow_add' => true,
                    'allow_delete' => true,
                    'prototype' => true,
                    'attr' => array(
                        'class' => 'media-gallery-collection',
                    ),
                ))
                ->add('imageAlt')
                ->add('class', ChoiceType::class, array(
                    'choices' => array('--1w' => true, '--2w' => false, ' ' => false),
                ))
                ->add('versionComment', CKEditorType::class, array(
                    'config' => array('toolbar' => 'minimal'),
                ))
                ->add('sortOrder')
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'CraftKeen\FCRBundle\Entity\RetailArt',
            'language' => 'CraftKeen\CMS\AdminBundle\Entity\Language'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'craftkeen_fcrbundle_retailart';
    }

}
