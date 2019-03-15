<?php

namespace CraftKeen\CMS\PageBundle\Form;

use CraftKeen\CMS\PageBundle\Entity\Page;
use CraftKeen\CMS\PageBundle\Entity\Route;
use CraftKeen\CMS\PageBundle\Repository\PageRepository;
use CraftKeen\CMS\UserBundle\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\Forms;

class PageForms extends Controller
{
    const PATERN_EMAIL = '^([a-z0-9_\.-]+)@([a-z0-9_\.-]+)\.([a-z\.]{2,6})$';

    /**
     * @var Registry
     */
    protected $doctrineManager;

    /**
     * Constructor
     *
     * @param $doctrineManager
     */
    public function __construct($doctrineManager)
    {
        $this->doctrineManager = $doctrineManager;
    }

    /**
     * @return FormInterface
     */
    public function buildFooterForm()
    {
        $formFactory = Forms::createFormFactory();
        $form = $formFactory
            ->createNamedBuilder('form-footer', 'form', null, [])
            ->add('email', EmailType::class, [
                'attr' => [
                    'pattern' => self::PATERN_EMAIL,
                ],
                'required' => true,
            ])
            ->add('leasing', CheckboxType::class, ['required' => false, 'label' => 'LEASING'])
            ->add('news', CheckboxType::class, ['required' => false, 'label' => 'NEWS'])
            ->add('careers', CheckboxType::class, ['required' => false, 'label' => 'CAREERS'])
            ->getForm();

        return $form;
    }

    /**
     * @param $pageId
     *
     * @return FormInterface
     */
    public function buildRouteForm($pageId)
    {
        /** @var EntityRepository $repository */
        $repository = $this->doctrineManager->getRepository(Route::class);
        $query = $repository->createQueryBuilder('p')
            ->where('p.pageId = :pageId')
            ->setParameter('pageId', $pageId)
            ->getQuery();

        $listRoute = $query->getResult();
        $listSlug = [];

        /** @var Route $val */
        foreach ($listRoute as $val) {
            $listSlug[$val->getSlug()] = $val->getSlug();
        }

        $formFactory = Forms::createFormFactory();

        $form = $formFactory->createNamedBuilder('form-route', 'form', null, [])
            ->add('id', HiddenType::class, ['required' => false])
            ->add('existRoute', ChoiceType::class, ['choices' => $listSlug, 'required' => true])
            ->add('createRoute', TextType::class, ['required' => false])
            ->add('pageId', HiddenType::class, ['required' => true])
            ->add('save', SubmitType::class, ['label' => 'Create Route'])
            ->add('delete', SubmitType::class, ['label' => 'Delete Route'])
            ->getForm();

        return $form;
    }

    /**
     * @param $routeForm
     * @param $pageId
     */
    public function processingRouteForm(FormInterface $routeForm, $pageId)
    {
        if ($routeForm->isSubmitted() && $routeForm->isValid()) {
            $routeEntity = new Route();
            /** @var EntityManager $em */
            $em = $this->doctrineManager->getManager();
            /** @var EntityRepository $repository */
            $repository = $this->doctrineManager->getRepository(Route::class);

            if ($routeForm->get('save')->isClicked()) {
                $routeEntity->setPageId($pageId); //??????????????????
                $routeEntity->setSlug(trim($routeForm['createRoute']->getData()));

                if (!empty($routeEntity->getSlug())) {
                    $isExistRoute = $repository->findOneBySlug($routeEntity->getSlug());

                    if ($isExistRoute == null) {
                        $em->persist($routeEntity);
                        $em->flush();
                    }
                }
            } else {
                $routeEntity->setSlug($routeForm['existRoute']->getData());
                $isExistRoute = $repository->findOneBySlug($routeEntity->getSlug());
                $em->remove($isExistRoute);
                $em->flush();
            }
        }
    }

    /**
     * @param $page
     * @param $user
     *
     * @return FormInterface
     */
    public function buildPageForm(Page $page, User $user)
    {
        $pageEntity = null;
        $listName = [];

        if ($user != null) {
            /** @var PageRepository $repository */
            $repository = $this->doctrineManager->getRepository(Page::class);
            $query = $repository->createQueryBuilder('p')
                ->where('p.status = :status')
                ->setParameter('status', 'draft')
                ->andWhere('p.copyOf = :parent')
                ->setParameter('parent', $page->getId())
                ->andWhere('p.updatedBy = :userId')
                ->setParameter('userId', $user->getId())
                ->orderBy('p.name')
                ->getQuery();
            $draftPage = $query->getOneOrNullResult();

            if ($draftPage != null) {
                $pageEntity = $draftPage;
            } else {
                $pageEntity = $page;
            }

            $pageEntity->setUpdated(new \DateTime());

            $query = $repository->createQueryBuilder('p')
                ->select('p.id, p.name')
                ->where('p.id <> :id')
                ->setParameter('id', $page->getId())
                ->orderBy('p.name')
                ->getQuery();

            $list = $query->getResult();

            foreach ($list as $val) {
                $listName[$val['id']] = $val['name'];
            }
        }

        $formFactory = Forms::createFormFactory();

        $form = $formFactory->createNamedBuilder('form-page', 'form', $pageEntity, [])
            ->add('id', HiddenType::class, ['required' => false])
            ->add('name', TextType::class, ['required' => true])
            ->add('title', TextType::class, ['required' => true])
            ->add('hero', TextType::class, ['required' => true])
            ->add('status', HiddenType::class, ['required' => false])
            ->add('lang', HiddenType::class, ['required' => false])
            ->add('langParent', HiddenType::class, ['required' => false])
            ->add('metaTitle', TextType::class, ['required' => true])
            ->add('metaDescription', TextType::class, ['required' => true])
            ->add('metaKeywords', TextType::class, ['required' => true])
            ->add('parent', ChoiceType::class, ['choices' => $listName, 'required' => false])
            ->add('createdBy', HiddenType::class, ['required' => false])
            ->add('updatedBy', HiddenType::class, ['required' => false])
            ->add('created', DateType::class, ['widget' => 'single_text', 'required' => false])
            ->add('updated', DateType::class, ['widget' => 'single_text', 'required' => false])
            ->add('version', HiddenType::class, ['required' => false])
            ->add('isIndexed', CheckboxType::class, ['required' => true])
            ->add('template', TextType::class, ['required' => true])
            ->add('copyOf', HiddenType::class, ['required' => false])
            ->add('save', SubmitType::class, ['label' => 'Save Draft'])
            ->add('toApprove', SubmitType::class, ['label' => 'To Approve'])
            ->add('delete', SubmitType::class, ['label' => 'Delete'])
            ->getForm();

        return $form;
    }

    /**
     * @param FormInterface $pageForm
     * @param $idUser
     */
    public function processingPageForm(FormInterface $pageForm, $idUser)
    {
        if ($pageForm->isSubmitted() && $pageForm->isValid()) {
            $pageEntity = $pageForm->getData();
            $pageEntity->setUpdated(new \DateTime());

            /** @var EntityManager $em */
            $em = $this->doctrineManager->getManager();

            if ($pageForm->get('save')->isClicked()) {
                if ($pageEntity->getStatus() == 'published') {
                    $new = new Page();
                    $new->setName($pageEntity->getName());
                    $new->setHero($pageEntity->getHero());
                    $new->setStatus('draft');
                    $new->setLang($pageEntity->getLang());
                    $new->setLangParent($pageEntity->getLangParent());
                    $new->setMetaTitle($pageEntity->getMetaTitle());
                    $new->setMetaDescription($pageEntity->getMetaDescription());
                    $new->setMetaKeywords($pageEntity->getMetaKeywords());
                    $new->setParent($pageEntity->getParent());
                    $new->setCreatedBy($pageEntity->getCreatedBy());
                    $new->setUpdatedBy($idUser);
                    $new->setCreated($pageEntity->getCreated());
                    $new->setUpdated(new \DateTime());
                    $new->setVersion($pageEntity->getVersion());
                    $new->setIsIndexed($pageEntity->getIsIndexed());
                    $new->setTemplate($pageEntity->getTemplate());
                    $new->setCopyOf($pageEntity);

                    $em->persist($new);
                    $em->flush($new);

                    return;
                }
            } elseif ($pageForm->get('toApprove')->isClicked() && $pageEntity->getStatus() == 'draft') {
                $pageEntity->setStatus('pending approval');
            } elseif ($pageForm->get('delete')->isClicked()) {
                $pageEntity->setStatus('deleted');
            }

            $em->persist($pageEntity);
            $em->flush($pageEntity);
        }
    }
}
