<?php

namespace CraftKeen\FCRBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use CraftKeen\FCRBundle\Entity\PropertyFilter;

class PropertyFilterType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
                //->add('property')
                ->add('isFilterGroceryAnchored', null, [
                    'label' => 'Grocery Anchored'
                ])
                ->add('isFilterUrbanRetail', null, [
                    'label' => 'Urban Retail'
                ])
                ->add('isFilterOfficeSpace', null, [
                    'label' => 'Office Space'
                ])
                ->add('isFilterUnderDevelopment', null, [
                    'label' => 'Under Development'
                ])
                ;                
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => PropertyFilter::class
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'craftkeen_fcrbundle_property_filter';
    }


}
