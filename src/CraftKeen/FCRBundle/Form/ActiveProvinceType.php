<?php

namespace CraftKeen\FCRBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use FM\ElfinderBundle\Form\Type\ElFinderType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Ivory\CKEditorBundle\Form\Type\CKEditorType;

class ActiveProvinceType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('provinceCode', TextType::class, ['label' => 'Province Abbreviation (e.g. BC)'])
				->add('provinceName', TextType::class, ['label' => 'Province Name (e.g. British Columbia)'])
				->add('description', TextType::class, ['label' => 'Province Description: (Must Keep %prop_count% variable to display the number of FCR properties within Province.)'])
				->add('totalArea', TextType::class, ['label' => 'Total Area (numbers only)'])
				->add('population', TextType::class, ['label' => 'Population (numbers only)'])
				->add('households', TextType::class, ['label' => 'Number of Households (numbers only)'])
				->add('icon', ElFinderType::class, ['instance' => 'form', 'enable' => true, 'label' => 'Province Icon (vector shape of province. Must get file from Craft+Keen)'])
				->add('iconWidth', TextType::class, ['label' => 'Icon Width (get Number from Craft+Keen)'])
				->add('labelLat', TextType::class, ['label' => 'Map Label Latitude (get from Craft+Keen)'])
				->add('labelLng', TextType::class, ['label' => 'Map Label Longitude (get from Craft+Keen)'])
				->add('markerLat', TextType::class, ['label' => 'Map Pin Latitude (get from Craft+Keen)'])
				->add('markerLng', TextType::class, ['label' => 'Map Pin Longitude (get from Craft+Keen)'])
                ->add('versionComment', CKEditorType::class, array(
					'config' => array('toolbar' => 'minimal'),
				))
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'CraftKeen\FCRBundle\Entity\ActiveProvince'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'craftkeen_fcrbundle_activeprovince';
    }


}
