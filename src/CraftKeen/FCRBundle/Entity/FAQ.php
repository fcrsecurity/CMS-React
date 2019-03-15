<?php

namespace CraftKeen\FCRBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * FAQ
 *
 * @ORM\Table(name="faq")
 * @ORM\Entity(repositoryClass="CraftKeen\FCRBundle\Repository\FAQRepository")
 * @Gedmo\Loggable(logEntryClass="CraftKeen\CMS\AdminBundle\Entity\Logs")
 */
class FAQ extends BaseEntity
{
    use HrEntitiesPermissionsTrait;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="category", type="string", length=50)
     * @Gedmo\Versioned
     */
    private $category;

    /**
     * @var string
     *
     * @ORM\Column(name="question", type="text")
     * @Gedmo\Versioned
     */
    private $question;

    /**
     * @var string
     *
     * @ORM\Column(name="answer", type="text")
     * @Gedmo\Versioned
     */
    private $answer;

    /**
     * @var FAQ
     *
     * Self Referencing
     * @ORM\ManyToOne(targetEntity="FAQ", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true, onDelete="CASCADE", referencedColumnName="id")
     */
    private $langParent;

    /**
     * @var FAQ
     *
     * One Office have One Copy of Office.
     * @ORM\OneToOne(targetEntity="FAQ", cascade={"persist"})
     * @ORM\JoinColumn(nullable=true, onDelete="CASCADE")
     */
    protected $copyOf;

    /**
     * Get Full Name of Manager
     *
     * @return string
     */
    public function __toString()
    {
        return $this->question;
    }

    /**
     * Set Id
     *
     * @param string $id
     *
     * @return FAQ
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set category
     *
     * @param string $category
     *
     * @return FAQ
     */
    public function setCategory($category)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return string
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Set question
     *
     * @param string $question
     *
     * @return FAQ
     */
    public function setQuestion($question)
    {
        $this->question = $question;

        return $this;
    }

    /**
     * Get question
     *
     * @return string
     */
    public function getQuestion()
    {
        return $this->question;
    }

    /**
     * Set answer
     *
     * @param string $answer
     *
     * @return FAQ
     */
    public function setAnswer($answer)
    {
        $this->answer = $answer;

        return $this;
    }

    /**
     * Get answer
     *
     * @return string
     */
    public function getAnswer()
    {
        return $this->answer;
    }

    /**
     * Set langParent
     *
     * @param FAQ $langParent
     *
     * @return FAQ
     */
    public function setLangParent(FAQ $langParent = null)
    {
        $this->langParent = $langParent;

        return $this;
    }

    /**
     * Get langParent
     *
     * @return FAQ
     */
    public function getLangParent()
    {
        return $this->langParent;
    }

    /**
     * Set copyOf
     *
     * @param FAQ $copyOf
     *
     * @return FAQ
     */
    public function setCopyOf(FAQ $copyOf = null)
    {
        $this->copyOf = $copyOf;

        return $this;
    }

    /**
     * Get copyOf
     *
     * @return FAQ
     */
    public function getCopyOf()
    {
        return $this->copyOf;
    }

    /**
     * Return showing fields
     *
     * @param $display
     *
     * @return array
     */
    public function getDisplayItems($display)
    {
        $baseList = [
            'id',
            'category',
            'question',
            'answer',
            'status',
        ];
        switch ($display) {
            case 'index':
                return $baseList;
                break;
            case 'view':
                return $baseList;
                break;
            case 'translate':
                return $baseList;
                break;
        }
        return [];
    }

    /**
     * @return string
     */
    public function getEntityBaseRoute() {
        return 'admin_faq_';
    }
}
