<?php

namespace CraftKeen\CMS\PageBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use CraftKeen\CMS\PageBundle\Form\DataTransformer\SerializedToFieldYMLTransformer;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class PageWidgetType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('page')
            ->add('widget')
            ->add('config', TextareaType::class, array(
                'attr' => array('class' => 'yml-editor', 'rows' => 5),
            ))
            ->add('data', TextareaType::class, array(
                'attr' => array('class' => 'yml-editor', 'rows' => 10),
            ))
            ->add('dataType')
            ->add('tplArea')
            //->add('status')
            //->add('copyOf')
            ;
        
        $builder->get('config')
            ->addModelTransformer(new SerializedToFieldYMLTransformer())
        ;
        $builder->get('data')
            ->addModelTransformer(new SerializedToFieldYMLTransformer())
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'CraftKeen\CMS\PageBundle\Entity\PageWidget'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'craftkeen_cms_pagebundle_pagewidget';
    }


}
