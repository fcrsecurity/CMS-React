<?php

namespace CraftKeen\FCRBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Ivory\CKEditorBundle\Form\Type\CKEditorType;
use FM\ElfinderBundle\Form\Type\ElFinderType;

class PeopleType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
                ->add('category', ChoiceType::class, [
                    'choices' => [
                        'Board of Directors' => 1,
                        'Executive Leadership' => 2
                    ]
                ])
                ->add('name')->add('position')
                ->add('description', CKEditorType::class)
                ->add('image', ElFinderType::class, ['instance' => 'form', 'enable' => true, 'label' => 'Headshot Image (size should be minimum 500x500 pixels and maximum 1000x1000 pixels)'])
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
            'data_class' => 'CraftKeen\FCRBundle\Entity\People'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'craftkeen_fcrbundle_people';
    }


}
