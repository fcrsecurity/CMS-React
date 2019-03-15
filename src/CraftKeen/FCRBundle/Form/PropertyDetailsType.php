<?php

namespace CraftKeen\FCRBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use FM\ElfinderBundle\Form\Type\ElFinderType;
use CraftKeen\FCRBundle\Entity\PropertyDetails;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Ivory\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class PropertyDetailsType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        //$language = $options['language'];
        $builder
            ->add('sqft', null, [
                'label' => 'Property Total SQ FT (enter numbers only ex. 50000)',
                'required' => true,
            ])
            ->add('description', CKEditorType::class, array(
                'config' => array('toolbar' => 'description'),
            ))
            ->add('leedDescription', null, [
                'label' => 'LEED/BOMA Description'
            ])
            ->add('marketingPdf', ElFinderType::class, [
                'label' => 'Marketing Brochure PDF',
                'instance' => 'form',
                'enable' => true,
                'required' => false,
            ])
            ->add('sitePlanPdf', ElFinderType::class, [
                'label' => 'Site Plan PDF',
                'instance' => 'form',
                'enable' => true,
                'required' => false,
            ])
            ->add('videoUrl', ElFinderType::class, [
                'label' => 'Gallery Video URL (mp4 file)',
                'instance' => 'form',
                'enable' => true,
                'required' => false,
            ])
            ->add('heroImage', ElFinderType::class, [
                'label' => 'Hero Image (Image size: 2446x1100 pixels)',
                'instance' => 'form',
                'required' => false,
                'enable' => true,
                'attr' => ['data-onchange-callback' => 'heroImageChangeCallback']
            ])
            ->add('heroImageAlt', TextType::class, [
                'label' => 'Hero Image Alt (AODA short description. <100 characters)',
                'required' => false,
            ])
            ->add('geoAddress1', TextType::class, [
                'label' => 'Property Address',
                'required' => true,
            ])
            ->add('geoAddress2', TextType::class, [
                'label' => 'Property Address 2',
                'required' => false,
            ])
            ->add('geoCity', TextType::class, [
                'label' => 'City',
                'required' => true,
            ])
            ->add('geoProvince', ChoiceType::class, [
                'label' => 'Province',
                'choices' => ['BC' => 'BC', 'AB' => 'AB', 'ON' => 'ON', 'QC' => 'QC'],
            ])
            // TODO: Move Province into Relation to Active Province
//            ->add('geoProvince', EntityType::class, array(
//                'class' => ActiveProvince::class,
//                'query_builder' => function (EntityRepository $er) use ($language) {
//                    return $er->createQueryBuilder('ap')
//                        ->where('ap.lang = :language')
//                        ->setParameter(':language', $language)
//                        ->andWhere('ap.status = :status')
//                        ->setParameter(':status', 'live')
//                        ;
//                }
//            ))
            ->add('geoProvinceRegion', ChoiceType::class, [
                'label' => 'Province Region',
                'choices' => ['Central' => 'Central', 'Western' => 'Western', 'Eastern' => 'Eastern'],
            ])
            ->add('geoCountry', TextType::class, [
                'label' => 'Country',
                'data' => 'Canada',
                'disabled' => true
            ])
            ->add('geoPostal', TextType::class, [
                'label' => 'Postal Code',
                'required' => true,
            ])
            ->add('geoIntersetion', TextType::class, [
                'label' => 'Intersection',
                'required' => false,
            ])
            ->add('geoLat', TextType::class, [
                'label' => 'Map Pin Latitude',
                'required' => false,
            ])
            ->add('geoLng', TextType::class, [
                'label' => 'Map Pin Longitude',
                'required' => false,
            ])
            ->add('seoTitle', TextType::class, [
                'label' => 'SEO: Title Tag (<60 characters)',
                'required' => false,
            ])
            ->add('seoDescription', TextType::class, [
                'label' => 'SEO: Meta Description (after 160 characters, description will be truncated)',
                'required' => false,
            ])
            ->add('seoKeywords', TextType::class, [
                'label' => 'SEO: Meta Keywords (make sure keywords match pages\'s content; separate keywords by comma)',
                'required' => false,
            ])
            ->add('seoIsIndex', null, [
                'label' => 'Google Index - check to make page show up for search engine results'
            ])
            ->add('socialFacebook', TextType::class, [
                'label' => 'Facebook URL',
                'required' => false,
            ])
            ->add('socialTwitter', TextType::class, [
                'label' => 'Twitter URL',
                'required' => false,
            ])
            ->add('socialUrl', TextType::class, [
                'label' => 'Property Website URL',
                'required' => false,
            ]);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => PropertyDetails::class,
            'language' => 'CraftKeen\CMS\AdminBundle\Entity\Language'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'craftkeen_fcrbundle_property_details';
    }
}
