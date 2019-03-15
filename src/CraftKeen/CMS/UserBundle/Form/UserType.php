<?php

namespace CraftKeen\CMS\UserBundle\Form;

use CraftKeen\CMS\UserBundle\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;

class UserType extends AbstractType
{

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('username')
            ->add('email')
            ->add('plainPassword', PasswordType::class)
            ->add('roles', ChoiceType::class, [
                'choices' => [
                    'Super Administrator' => 'ROLE_ADMINISTRATOR',
                    'Administrator' => 'ROLE_ADMINISTRATOR',
                    'Contributor' => 'ROLE_CONTRIBUTOR',
                    'Editor' => 'ROLE_EDITOR',
                    'Approver' => 'ROLE_APPROVER',
                    'Regular User' => 'ROLE_USER',
                    // TODO: Move roles to the database.
                    'Leasing' => 'ROLE_LEASING',
                    'Regional Leasing Coordinators' => 'ROLE_LEASING_REGIONAL_COORDINATORS',
                    'Investors' => 'ROLE_INVESTORS',
                    'Careers' => 'ROLE_HR',
                    'Brochure Editor'           => 'ROLE_BROCHURE_EDITOR',
                    'Brochure Approver'         => 'ROLE_BROCHURE_APPROVER',
                    'Brochure Administrator'    => 'ROLE_BROCHURE_ADMINISTRATOR',

                ],
                'expanded' => true,
                'multiple' => true,
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
        return 'craftkeen_cms_userbundle_user';
    }
}
