<?php

namespace CraftKeen\FCRBundle\Form;

use Ivory\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use FM\ElfinderBundle\Form\Type\ElFinderType;
use CraftKeen\CMS\PageBundle\Entity\PageWidget;
use CraftKeen\CMS\PageBundle\Entity\Page;
use CraftKeen\FCRBundle\Entity\Property;
use Doctrine\ORM\EntityRepository;

class PropertyFeatureSliderType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $language = $options['data']->getLang();

        $builder->add('title')
				->add('image', ElFinderType::class, ['instance' => 'form', 'enable' => true])
				->add('imageAlt')
				->add('property', EntityType::class, array(
					'class' => Property::class,
					'query_builder' => function(EntityRepository $er) use ($language) {
						return $er->createQueryBuilder('p')
                            ->where('p.lang = :language')
                            ->setParameter(':language', $language)
                            ->andWhere('p.status = :status')
                            ->setParameter(':status', 'live')
                            ->orderBy("p.childName", 'ASC')
                        ;
					}
				))
                // Show only pages with FeatureSlider widget
				->add('page', EntityType::class, array(
					'class' => Page::class,
					'query_builder' => function(EntityRepository $er) use ($language) {
						return $er->createQueryBuilder('p')
                            ->leftJoin(PageWidget::class, "pw", "WITH", "p.id = pw.page")
                            ->where('p.lang = :language')
                            ->setParameter(':language', $language)
                            ->andWhere('p.status = :status')
                            ->setParameter(':status', 'live')
                            ->andWhere('pw.dataType = :dataType')
                            ->setParameter(':dataType', 'FeatureSlider')
                        ;
					}
				))
                ->add('versionComment', CKEditorType::class, array(
                    'config' => array('toolbar' => 'minimal'),
                ))
				->add('sortOrder')
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'CraftKeen\FCRBundle\Entity\PropertyFeatureSlider',
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'craftkeen_fcrbundle_propertyfeatureslider';
    }


}
