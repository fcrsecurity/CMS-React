<?php

namespace CraftKeen\BrochureBuilderBundle\Form;

use CraftKeen\CMS\UserBundle\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Validator\Constraints\Choice;


class UserEditType extends AbstractType
{

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('username')
            ->add('email')
            ->add('roles', ChoiceType::class, [
                'choices' => [
                    'Brochure Editor'           => 'ROLE_BROCHURE_EDITOR',
                    'Brochure Approver'         => 'ROLE_BROCHURE_APPROVER',
                ],
                'expanded'      => true,
                'multiple'      => true,
                'constraints'   => [
                    new Choice([
                        'choices' => [
                            'ROLE_BROCHURE_EDITOR',
                            'ROLE_BROCHURE_APPROVER'
                        ],
                        'multiple'      => true,
                        'min'           => 1,
                        'minMessage'    => 'Select at least one role',
                    ])
                ]
            ])
            ->add('enabled');
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'brochure_user';
    }
}
