<?php

namespace CraftKeen\FCRBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Ivory\CKEditorBundle\Form\Type\CKEditorType;
use FM\ElfinderBundle\Form\Type\ElFinderType;

class PressReleaseType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('date')
            ->add('title', TextType::class, ['attr' => [
                'class' => 'text-to-slug',
                'data-to-slug-target' => 'slug-target',
                'data-to-slug-allow-overwrite' => 'false'
            ]])
            ->add('slug', TextType::class, ['required' => false, 'attr' => ['class' => 'slug-target']])
            ->add('content', CKEditorType::class, ['required' => true, 'empty_data' => ''])
            ->add('pdfFile', ElFinderType::class, ['instance' => 'form', 'enable' => true, 'required' => false])
            ->add('isHidden')
            ->add('versionComment', CKEditorType::class, array(
                'config' => array('toolbar' => 'minimal'),
            ))
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'CraftKeen\FCRBundle\Entity\PressRelease'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'craftkeen_fcrbundle_pressrelease';
    }
}
