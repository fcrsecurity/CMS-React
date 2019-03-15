<?php

namespace CraftKeen\FCRBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class PropertySubmissionType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
				->add('name')
				->add('phone')
				->add('email')
				->add('inquryType', ChoiceType::class, array(
					'choices'  => array(
						'Select Category' => '',
						'All' => 'All',
						'Grocery Anchored' => 'Grocery Anchored',
						'Retail' => 'Retail',
						'Office Space' => 'Office Space',
						'LEED' => 'LEED',
						'BOMA' => 'BOMA',
						'Has Availability' => 'Has Availability',
					),
				))
				->add('squareFootage')
				->add('comment');
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'CraftKeen\FCRBundle\Entity\PropertySubmission'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'craftkeen_fcrbundle_propertysubmission';
    }


}
