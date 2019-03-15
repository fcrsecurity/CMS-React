<?php

namespace CraftKeen\FCRBundle\Form;

use Ivory\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class OfficeType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('header')
                ->add('address')
                ->add('city')
                ->add('province')
                ->add('postal')
                ->add('tel',  TextType::class, ['required'   => false,])
                ->add('tollfree',  TextType::class, ['required'   => false,])
                ->add('fax',  TextType::class, ['required'   => false,])
//                ->add('status')
                ->add('isMain')
                ->add('versionComment', CKEditorType::class, array(
                    'config' => array('toolbar' => 'minimal'),
                ))
                ->add('sortOrder')
        ;
                //->add('lang')
                //->add('langParent');
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'CraftKeen\FCRBundle\Entity\Office'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'craftkeen_fcrbundle_office';
    }


}
