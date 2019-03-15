<?php

namespace CraftKeen\FCRBundle\Import;

use Doctrine\ORM\EntityManager;
use CraftKeen\FCRBundle\Entity\PressRelease;
use Symfony\Component\Config\Definition\Exception\Exception;

class PressReleaseImport extends AbstractImport
{
    /**
     * PressReleaseImport constructor
     *
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        parent::__construct($em);
    }

    /**
     * Erases Tables Before Import
     *
     * @return mixed
     */
    public function eraseTables()
    {
        return $this->em->getRepository(PressRelease::class)->truncate();
    }

    /**
     * Load Dependencies, other tables
     *
     * @return mixed
     */
    public function loadDependencies()
    {
        if (!isset($this->records['press_releases'])) {
            throw new Exception('Dependencies was not load for Press Releases');
        }

        return $this->loadPressRelease($this->records['press_releases']);
    }

    /**
     * Load Press Releases
     *
     * @param array $items
     *
     * @return string
     */
    private function loadPressRelease($items)
    {
        $access = array(
            'CREATE' => null,
            'READ' => null,
            'UPDATE' =>
                [
                    0 => 'ROLE_INVESTORS',
                ],
            'DELETE' => null,
            'APPROVE' =>
                [
                    0 => 'ROLE_INVESTORS',
                ],
        );
        $count = 0;
        $slugs = [];
        foreach ($items as $item) {
            $slugEn = $this->slugify($item['title']);
            $slugFr = $this->slugify($this->processFrenchRecord($item, 'title') . '-fr');

            $slugs[$slugEn][] = 1;
            $slugs[$slugFr][] = 1;
            $slugCountEn = count($slugs[$slugEn]);
            $slugCountFr = count($slugs[$slugFr]);

            if ($slugCountEn > 1) {
                $slugCountEn++;
                $slugEn .= '-' . $slugCountEn;
            }

            if ($slugCountFr > 1) {
                $slugCountFr++;
                $slugFr .= '-' . $slugCountFr;
            }

            $date = new \DateTime($item['date']);
            $pressRelease = new PressRelease();
            $pressRelease->setContent($item['content']);
            $pressRelease->setDate($date);
            $pressRelease->setStatus('live');
            $pressRelease->setIsHidden($item['is_hidden']);
            $pressRelease->setLang($this->lang['en']);
            $pressRelease->setLangParent(null);
            $pressRelease->setPdfFile($item['pdf_en']);
            $pressRelease->setSlug($slugEn);
            $pressRelease->setTitle($item['title']);
            $pressRelease->setCreatedBy($this->user);
            $pressRelease->setCreated(new \DateTime());
            $pressRelease->setVersion(1);
            $pressRelease->setSite($this->site);
            $pressRelease->setVersionComment("Initial");
            $pressRelease->setAccess(serialize($access));
            $pressRelease->setSortOrder($count);

            $this->em->persist($pressRelease);
            $this->em->flush();

            // Add translation
            $pressReleaseFr = new PressRelease();
            $pressReleaseFr->setContent($this->processFrenchRecord($item, 'content'));
            $pressReleaseFr->setDate($date);
            $pressReleaseFr->setStatus('live');
            $pressReleaseFr->setIsHidden($item['is_hidden']);
            $pressReleaseFr->setLang($this->lang['fr']);
            $pressReleaseFr->setLangParent($pressRelease);
            $pressReleaseFr->setPdfFile(($item['pdf_fr'] != null) ? $item['pdf_fr'] : $item['pdf_en']);
            $pressReleaseFr->setSlug($slugFr);
            $pressReleaseFr->setTitle($this->processFrenchRecord($item, 'title'));
            $pressReleaseFr->setCreatedBy($this->user);
            $pressReleaseFr->setCreated(new \DateTime());
            $pressReleaseFr->setVersion(1);
            $pressReleaseFr->setSite($this->site);
            $pressReleaseFr->setVersionComment("Initial");
            $pressReleaseFr->setAccess(serialize($access));
            $pressReleaseFr->setSortOrder($count);

            $this->em->persist($pressReleaseFr);
            $this->em->flush();

            $count++;
        }

        return "Imported $count Press Releases";
    }

    private function processFrenchRecord($item, $key)
    {
        if ($item[$key . '_fr'] != null &&
            strtolower($item[$key . '_fr']) != "null" &&
            strlen($item[$key . '_fr']) > 0
        ) {
            return $item[$key . '_fr'];
        }
        return $item[$key];
    }
}
