<?php

namespace CraftKeen\CMS\PageBundle\Form;

use CraftKeen\CMS\PageBundle\Entity\Page;
use CraftKeen\CMS\UserBundle\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use FM\ElfinderBundle\Form\Type\ElFinderType;
use CraftKeen\CMS\PageBundle\Form\DataTransformer\SerializedToFieldYMLTransformer;
use CraftKeen\CMS\PageBundle\Form\DataTransformer\JSONToFieldYMLTransformer;

class PageType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('site')
            ->add('parent')
            ->add('name')
            ->add('heroTitle')
            ->add('hero', ElFinderType::class, ['instance' => 'form', 'enable' => true, 'required' => false,])
            ->add('heroVideo', ElFinderType::class, ['instance' => 'form', 'enable' => true, 'required' => false,])
            ->add('metaTitle')
            ->add('metaDescription')
            ->add('metaKeywords')
            ->add('isIndexed')
            ->add('versionComment')
            ->add('layout', TextareaType::class, [
                'label' => 'Layout - YML Representation',
                'attr' => ['class' => 'yml-editor', 'rows' => 20],
            ])
            ->add('template', ChoiceType::class, ['choices' => [
                'Main' => 'main',
                'Page' => 'page',
                'Portfolio Leasing' => 'portfolio-leasing',
            ]]);

        $builder->get('layout')
            ->addModelTransformer(new JSONToFieldYMLTransformer());

        if (in_array('ROLE_ADMINISTRATOR', $options['user']->getRoles())) {
            $builder
                ->add('access', TextareaType::class, [
                    'attr' => ['class' => 'yml-editor', 'rows' => 10],
                ])
                ->get('access')
                ->addModelTransformer(new SerializedToFieldYMLTransformer());
        }
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Page::class,
            'user' => User::class
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'craftkeen_cms_pagebundle_page';
    }
}
