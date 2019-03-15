<?php

namespace CraftKeen\Bundle\TranslationBundle\Form\Type;

use CraftKeen\Bundle\ComponentBundle\Form\Type\AbstractFromType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Intl\Intl;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LanguageType extends AbstractFromType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('code', ChoiceType::class, [
            /** TODO: Remove already defined languages */
            'choices' => array_flip(Intl::getLocaleBundle()->getLocaleNames('en')),
            'required' => true,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => $this->dataClass,
        ]);
    }
}
