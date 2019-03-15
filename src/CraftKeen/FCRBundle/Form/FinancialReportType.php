<?php

namespace CraftKeen\FCRBundle\Form;

use Ivory\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use FM\ElfinderBundle\Form\Type\ElFinderType;

class FinancialReportType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
                ->add('year')
                ->add('q1', ElFinderType::class, ['instance' => 'form', 'enable' => true])
                ->add('q2', ElFinderType::class, ['instance' => 'form', 'enable' => true, 'required'   => false,])
                ->add('q3', ElFinderType::class, ['instance' => 'form', 'enable' => true, 'required'   => false,])
                ->add('q4', ElFinderType::class, ['instance' => 'form', 'enable' => true, 'required'   => false,])
                ->add('annual', ElFinderType::class, ['instance' => 'form', 'enable' => true, 'required'   => false,])
                ->add('versionComment', CKEditorType::class, array(
                    'config' => array('toolbar' => 'minimal'),
                ))
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
            'data_class' => 'CraftKeen\FCRBundle\Entity\FinancialReport'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'craftkeen_fcrbundle_financialreport';
    }


}
