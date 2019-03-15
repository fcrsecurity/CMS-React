<?php

namespace CraftKeen\CMS\AdminBundle\Entity;

use Gedmo\Loggable\Entity\MappedSuperclass\AbstractLogEntry;
use Doctrine\ORM\Mapping as ORM;

/**
 * Logs
 *
 * @ORM\Table(name="logs")
 * @ORM\Entity(repositoryClass="CraftKeen\CMS\AdminBundle\Repository\LogsRepository")
 *
 */
class Logs extends AbstractLogEntry
{
}
