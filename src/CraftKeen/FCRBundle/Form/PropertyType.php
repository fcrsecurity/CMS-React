<?php

namespace CraftKeen\FCRBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use FM\ElfinderBundle\Form\Type\ElFinderType;
use CraftKeen\FCRBundle\Entity\PropertyDetails;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class PropertyType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('code')
                ->add('parentName')
                ->add('childName')
                ->add('thumbnail', ElFinderType::class, [
                    'label' => 'Thumbnail Image (Square Size: minimum 800x800 pixels and maximum 1600x1600 pixels)',
                    'instance' => 'form',
                    'enable' => true,
                ])
                ->add('managers', null, [
                    'label' => 'Leasing & PM Contacts (maximum 3 can be selected)'
                ])
                ->add('tenants', null, [
                    'label' => 'Tenants Logos (minimum 3 must be selected)'
                ])
                ->add('gallery')
                ->add('vacancyList')
                ->add('isVacant')
                ->add('isGreen')
                ->add('isBoma')
                ->add('isHidden')
                ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'CraftKeen\FCRBundle\Entity\Property'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'craftkeen_fcrbundle_property';
    }


}
