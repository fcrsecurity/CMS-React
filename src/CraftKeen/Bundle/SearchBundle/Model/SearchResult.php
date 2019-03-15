<?php

namespace CraftKeen\Bundle\SearchBundle\Model;

use Doctrine\Common\Collections\ArrayCollection;

class SearchResult
{
    /**
     * @var int
     */
    protected $matchesFound = 0;

    /**
     * @var ArrayCollection
     */
    protected $matches;

    /**
     * {@inheritdoc}
     */
    public function __construct()
    {
        $this->matches = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getMatchesFound()
    {
        return $this->matchesFound;
    }

    /**
     * @param int $matchesFound
     *
     * @return $this
     */
    public function setMatchesFound($matchesFound)
    {
        $this->matchesFound = $matchesFound;

        return $this;
    }

    /**
     * @return array
     */
    public function getMatches()
    {
        return $this->matches->toArray();
    }

    /**
     * @param array $matches
     *
     * @return $this
     */
    public function setMatches(array $matches)
    {
        $this->matches->clear();

        foreach ($matches as $match) {
            $this->addMatch($match);
        }

        return $this;
    }

    /**
     * @param SearchItem $match
     *
     * @return $this
     */
    public function addMatch(SearchItem $match)
    {
        if (!$this->matches->contains($match)) {
            $this->matches->add($match);
        }

        return $this;
    }
}
