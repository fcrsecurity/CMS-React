<?php

namespace CraftKeen\BrochureBuilderBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BrochureContactType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('image')
            ->add('lifestyleImage')
            ->add('name')
            ->add('address1')
            ->add('address2')
            ->add('leasingFirstName')
            ->add('leasingLastName')
            ->add('leasingType')
            ->add('leasingTitle')
            ->add('leasingEmail')
            ->add('leasingFax')
            ->add('leasingPhone')
            ->add('leasingPhoneExtension')
            ->add('locationName')
            ->add('locationAddress1')
            ->add('locationAddress2')
            ->add('locationLatitude')
            ->add('locationLongitude')
            ->add('brochure');
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'CraftKeen\BrochureBuilderBundle\Entity\BrochureContact'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'craftkeen_brochurebuilderbundle_brochurecontact';
    }


}
