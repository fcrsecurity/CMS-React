<?php

namespace CraftKeen\CMS\MenuBundle\Form;

use CraftKeen\CMS\MenuBundle\Entity\Menu;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use CraftKeen\CMS\PageBundle\Entity\Page;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;

class MenuType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $language = $options['data']->getLang();
        $menuLocation = $options['data']->getType();

        $builder
            ->add('itemType', ChoiceType::class, [
                'choices' => ['Page' => 'page', 'Custom link' => 'custom'],
                'required' => true
            ])
            ->add('name')
            ->add('url')
            ->add('page', EntityType::class, [
                'class' => Page::class,
                'required' => false,
                'query_builder' => function (EntityRepository $er) use ($language) {
                    return $er->createQueryBuilder('p')
                        ->where('p.lang = :language')
                        ->setParameter(':language', $language)
                        ->andWhere('p.status = :status')
                        ->setParameter(':status', 'live')
                        ->orderBy("p.name", 'ASC');
                }
            ])
            ->add('type')
            ->add('targetBlank')
            ->add('parent', EntityType::class, [
                'class' => Menu::class,
                'required' => false,
                'query_builder' => function (EntityRepository $er) use ($language, $menuLocation) {
                    return $this->getParentItems($er, $menuLocation, $language);
                }
            ])
            ->add('sortOrder');
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Menu::class,
        ]);
    }

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return 'craftkeen_cms_menubundle_menu';
    }

    /**
     * @param EntityRepository $er
     * @param $menuLocation
     * @param $language
     *
     * @return QueryBuilder
     */
    public function getParentItems(EntityRepository $er, $menuLocation, $language)
    {
        $parentItems = $er->createQueryBuilder('m');

        if (!is_null($menuLocation)) {
            $parentItems->andWhere('m.type = :menuLocation')
                ->setParameter(':menuLocation', $menuLocation);
        }

        $parentItems
            ->andWhere('m.lang = :language')
            ->setParameter(':language', $language)
            ->andWhere('m.status = :status')
            ->setParameter(':status', 'live')
            ->orderBy("m.name", 'ASC');
        return $parentItems;
    }
}
