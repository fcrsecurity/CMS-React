<?php

namespace CraftKeen\FCRBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Ivory\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class FAQType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('category', ChoiceType::class, [
            'choices' => [
                'Investors' => 2,
                'Careers' => 1
            ]
        ])
        ->add('question')
        ->add('answer', CKEditorType::class, [
            'config' => [
                'enterMode' => 'CKEDITOR.ENTER_BR'
            ]
        ])
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
            'data_class' => 'CraftKeen\FCRBundle\Entity\FAQ'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'craftkeen_fcrbundle_faq';
    }


}
