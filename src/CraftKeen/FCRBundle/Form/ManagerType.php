<?php

namespace CraftKeen\FCRBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use FM\ElfinderBundle\Form\Type\ElFinderType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Ivory\CKEditorBundle\Form\Type\CKEditorType;

class ManagerType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('type', ChoiceType::class, [
					'choices' => [
						'Property Manager' => 'management',
						'Leasing Rep' => 'leasing'
					]
				])
				->add('image', ElFinderType::class, [
                    'label' => 'Headshot Image (optional. If no image, default shows. Image size should be minimum 500x500 pixels and 1000x1000 pixels)',
				    'instance' => 'form',
                    'enable' => true,
                    'required'   => false,
                ])
				->add('imageAlt', TextType::class, [
                    'label' => 'Headshot Alt (AODA short description. <100 characters)',
                    'required'   => false,
                ])
				->add('firstName')
				->add('lastName')
				->add('title')
				->add('email')
				->add('fax', null, [
                    'label' => 'Fax (enter just number)',
                    'required'   => false,
                ])
				->add('phone', null, [
                    'label' => 'Phone (enter just number)',
                    'required'   => false,
                ])
				->add('phoneExtension', null, [
                    'label' => 'Phone Extension (e.g. ex 254)',
                    'required'   => false,
                ])
				->add('tollfree', null, [
                    'label' => 'Toll-Free (enter just number)',
                    'required'   => false,
                ])
                ->add('sortOrder',TextType::class, ['disabled' => true])
                ->add('versionComment', CKEditorType::class, array(
                    'config' => array('toolbar' => 'minimal'),
                ));
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'CraftKeen\FCRBundle\Entity\Manager'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'craftkeen_fcrbundle_manager';
    }


}
