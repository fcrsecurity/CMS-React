<?php

namespace CraftKeen\BrochureBuilderBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BrochureAerialType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('image')
            ->add('annualAverageDailyTraffic')
            ->add('population1km')
            ->add('household1km')
            ->add('householdIncome1km')
            ->add('population3km')
            ->add('household3km')
            ->add('householdIncome3km')
            ->add('population5km')
            ->add('household5km')
            ->add('householdIncome5km')
            ->add('brochure');
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'CraftKeen\BrochureBuilderBundle\Entity\BrochureAerial'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'craftkeen_brochurebuilderbundle_brochureaerial';
    }


}
