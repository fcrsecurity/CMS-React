<?php

namespace CraftKeen\FCRBundle\Form;

use Ivory\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use FM\ElfinderBundle\Form\Type\ElFinderType;

class RetailArtGalleryType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
                ->add('image', ElFinderType::class, [
                    'instance' => 'form',
                    'enable' => true,
                    'attr' => [
                        'data-onchange-callback' => 'galleryImageChangeCallback'
                    ]
                ])
                ->add('imageAlt')
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
            'data_class' => 'CraftKeen\FCRBundle\Entity\RetailArtGallery'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'craftkeen_fcrbundle_retailartgallery';
    }


}
