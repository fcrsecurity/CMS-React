<?php

namespace CraftKeen\FCRBundle\Form;

use CraftKeen\FCRBundle\Model\PropertySearchModel;
use CraftKeen\FCRBundle\Provider\PropertySearchProvider;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class PropertySearchType
 *
 */
class PropertySearchType extends AbstractType
{
    /**
     * @var FilterChoicesProvider
     */
    protected $filterChoicesProvider;

    /**
     * PropertySearchType constructor.
     *
     * @param PropertySearchProvider $filterChoicesProvider
     */
    public function __construct(PropertySearchProvider $filterChoicesProvider)
    {
        $this->filterChoicesProvider = $filterChoicesProvider;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('isAvailable', CheckboxType::class, [
            'label' => 'fcr.property_search.is_vacant.label',
            'required' => false,
        ]);

        $builder->add('category', ChoiceType::class, [
            'choices' => $this->filterChoicesProvider->getCategoryTypeChoices(),
            'placeholder' => 'fcr.property_search.category.placeholder',
            //'translation_domain'=> 'messages',
            'required' => false,
        ]);

        $builder->add('city', ChoiceType::class, [
            'choices' => $this->filterChoicesProvider->getCityTypeChoices(),
            'placeholder' => 'fcr.property_search.city.placeholder',
            //'translation_domain'=> 'messages',
            'required' => false,
        ]);

        $builder->add('sqft_min', TextType::class, [
            'label' => 'fcr.property_search.sqft_min.label',
            'required' => false,
        ]);

        $builder->add('sqft_max', TextType::class, [
            'label' => 'fcr.property_search.sqft_max.label',
            'required' => false,
        ]);

        $builder->add('keyword', TextType::class, [
            'label' => 'fcr.property_search.keyword.label',
            'required' => false,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => PropertySearchModel::class,
            'method' => 'GET',
            'csrf_protection' => true,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return '';
    }
}
