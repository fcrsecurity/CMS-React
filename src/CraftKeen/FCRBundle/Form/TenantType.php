<?php

namespace CraftKeen\FCRBundle\Form;

use Ivory\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use FM\ElfinderBundle\Form\Type\ElFinderType;

class TenantType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('image', ElFinderType::class, [
                'label' => 'Tenant Logo',
                'instance' => 'form',
                'enable' => true
            ])
            ->add('imageAlt', TextType::class, [
                'label' => 'Tenant Logo Alt (AODA short description. <100 characters)',
                'required'   => false,
            ])
            ->add('versionComment', CKEditorType::class, array(
                'config' => array('toolbar' => 'minimal'),
            ))
            ->add('sortOrder',TextType::class, ['disabled' => true])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'CraftKeen\FCRBundle\Entity\Tenant'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'craftkeen_fcrbundle_tenant';
    }


}
