<?php

namespace CraftKeen\BrochureBuilderBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BrochureCoverType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('city')
            ->add('province')
            ->add('image')
            ->add('tagline')
            ->add('description')
            ->add('tenants')
            ->add('brochure');
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'CraftKeen\BrochureBuilderBundle\Entity\BrochureCover'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'craftkeen_brochurebuilderbundle_brochurecover';
    }


}
