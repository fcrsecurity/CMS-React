<?php

namespace CraftKeen\FCRBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use CraftKeen\FCRBundle\Entity\PropertyGallery;
use FM\ElfinderBundle\Form\Type\ElFinderType;

class PropertyGalleryType extends AbstractType
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
                ->add('imageAlt', TextType::class, [
                    'label' => 'Image Alt (AODA Short Description < 100 characters)',
                ])
                ;                
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => PropertyGallery::class,
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'craftkeen_fcrbundle_property_gallery';
    }


}
