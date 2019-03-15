<?php

namespace CraftKeen\FCRBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AodaLongDescriptionType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('objectClass', ChoiceType::class, [
                     'label' => 'Object ID',
                     'choices'  => [
                         'Active Province' => 'CraftKeenFCRBundle:ActiveProvince',
                         'Careers Employee' => 'CraftKeenFCRBundle:CareersEmployee',
                         'Careers Slider' => 'CraftKeenFCRBundle:CareersSlider',
                         'Manager' => 'CraftKeenFCRBundle:Manager',
                         'People' => 'CraftKeenFCRBundle:People',
                         'Property' => 'CraftKeenFCRBundle:Property',
                         'Property Details' => 'CraftKeenFCRBundle:PropertyDetails',
                         'Property Feature Slider' => 'CraftKeenFCRBundle:PropertyFeatureSlider',
                         'Property Gallery' => 'CraftKeenFCRBundle:PropertyGallery',
                         'Retail Art' => 'CraftKeenFCRBundle:RetailArt',
                         'Retail Art Gallery' => 'CraftKeenFCRBundle:RetailArtGallery',
                         'Tenant' => 'CraftKeenFCRBundle:Tenant',
                     ]])
                ->add('objectId',TextType::class, ['label' => 'Object ID'])
                ->add('fieldName',ChoiceType::class, [
                    'label' => 'Field Name',
                    'choices' => [
                        'Image' => 'image',
                        'Icon' => 'icon',
                        'Img' => 'img',
                        'Background' => 'background',
                        'Hero image' => 'hero_image',
                        'Thumbnail' => 'thumbnail',
                    ]])
                ->add('type',TextType::class, ['label' => 'Type'])
                ->add('longDescription', TextType::class, ['label' => 'Aoda Long Description'])
                //->add('sortOrder',TextType::class, ['label' => 'Sort Order'])
                ->add('site')
                ->add('lang');


        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            $data = $event->getData();
            $form = $event->getForm();

            if (!$data || null === $data->getId()) {
                $form->add('access',TextType::class, [
                    'label' => 'Access',
                    'required' => false,
                    'disabled' => true
                ]);
            }
            else {
                $form->add('access',TextType::class, [
                    'label' => 'Access',
                    'required' => false,
                ]);
            }
        });
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'CraftKeen\FCRBundle\Entity\AodaLongDescription'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'craftkeen_fcrbundle_aodalongdescription';
    }
}
