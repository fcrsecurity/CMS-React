<?php

namespace CraftKeen\FCRBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use CraftKeen\FCRBundle\Entity\PropertyDemographic;

class PropertyDemographicType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('annualAverageDailyTraffic', NumberType::class, [
                'label' => 'Annual Average Daily Traffic',
                'required' => false,
            ])
            ->add('population1km', NumberType::class, [
                'label' => 'Population 1km',
                'required' => false,
            ])
            ->add('household1km', NumberType::class, [
                'label' => 'Household 1km',
                'required' => false,
            ])
            ->add('householdIncome1km', NumberType::class, [
                'label' => 'Household Income 1km',
                'required' => false,
            ])
            ->add('population3km', NumberType::class, [
                'label' => 'Population 3km',
                'required' => false,
            ])
            ->add('household3km', NumberType::class, [
                'label' => 'Household 3km',
                'required' => false,
            ])
            ->add('householdIncome3km', NumberType::class, [
                'label' => 'Household Income 3km',
                'required' => false,
            ])
            ->add('population5km', NumberType::class, [
                'label' => 'Population 5km',
                'required' => false,
            ])
            ->add('household5km', NumberType::class, [
                'label' => 'Household 5km',
                'required' => false,
            ])
            ->add('householdIncome5km', NumberType::class, [
                'label' => 'householdIncome 5km',
                'required' => false,
            ]);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => PropertyDemographic::class
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'craftkeen_fcrbundle_property_demographic';
    }
}
