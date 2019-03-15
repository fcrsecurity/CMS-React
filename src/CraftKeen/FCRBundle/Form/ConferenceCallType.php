<?php

namespace CraftKeen\FCRBundle\Form;

use Ivory\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use FM\ElfinderBundle\Form\Type\ElFinderType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;
use CraftKeen\FCRBundle\Entity\PressRelease;
use CraftKeen\FCRBundle\Entity\FinancialReport;

class ConferenceCallType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
		$language = $options['language'];

        $builder
				->add('category', ChoiceType::class, [
					'choices' => [
						'General' => 'general',
						'AGM' => 'agm',
					]
				])
				->add('title')
				->add('date')
				->add('listenLink', TextType::class, ['required' => false])
				->add('slidesLink', ElFinderType::class, ['instance' => 'form', 'enable' => true, 'required' => false])
				->add('pressRelease', EntityType::class, array(
					'required'   => false,
					'class' => PressRelease::class,
					'query_builder' => function(EntityRepository $er) use ($language) {
						return $er->createQueryBuilder('p')
								->where('p.lang = :language')
								->setParameter(':language', $language)
								->orderBy('p.date', 'DESC');
					}
				))
				->add('quarterlyReport', EntityType::class, array(
					'required'   => false,
					'label' => 'Financial Report Year',
					'class' => FinancialReport::class,
					'query_builder' => function(EntityRepository $er) use ($language) {
						return $er->createQueryBuilder('f')
								->where('f.lang = :language')
								->setParameter(':language', $language)
								->orderBy('f.year', 'DESC');
					}
				))
				->add('quarterlyReportFileName', ChoiceType::class, [
					'required'   => false,
					'choices' => [
						'Q1' => 'q1',
						'Q2' => 'q2',
						'Q3' => 'q3',
						'Q4' => 'q4',
						'Annual' => 'annual',
					]
				])
				//->add('lang')
				//->add('langParent')
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
            'data_class' => 'CraftKeen\FCRBundle\Entity\ConferenceCall',
			'language' => 'CraftKeen\CMS\AdminBundle\Entity\Language'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'craftkeen_fcrbundle_conferencecall';
    }


}
