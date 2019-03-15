<?php

namespace CraftKeen\FCRBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use CraftKeen\FCRBundle\Entity\CareerPositionSubmission;
use EWZ\Bundle\RecaptchaBundle\Form\Type\EWZRecaptchaType;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;

class CareerPositionSubmissionType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
                ->add('positionId')
                ->add('firstName', TextType::class,[
                    'constraints' => [
                        new NotBlank()
                    ]
                ])
                ->add('lastName', TextType::class,[
                    'constraints' => [
                        new NotBlank()
                    ]
                ])
                ->add('email', TextType::class,[
                    'constraints' => [
                        new NotBlank(),
                        new Email()
                    ]
                ])
                ->add('address', TextType::class,[
                    'constraints' => [
                        new NotBlank()
                    ]
                ])
                ->add('city', TextType::class,[
                    'constraints' => [
                        new NotBlank()
                    ]
                ])
                ->add('province')
                ->add('postal', TextType::class,[
                    'constraints' => [
                        new NotBlank()
                    ]
                ])
                ->add('phone', TextType::class,[
                    'constraints' => [
                        new NotBlank()
                    ]
                ])
                ->add('resume', FileType::class, array('data_class' => null))
                ->add('recaptcha', EWZRecaptchaType::class)
                ;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => CareerPositionSubmission::class,
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'craftkeen_fcrbundle_careerpositionsubmission';
    }


}
