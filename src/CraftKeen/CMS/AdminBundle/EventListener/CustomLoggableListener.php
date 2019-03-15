<?php

namespace CraftKeen\CMS\AdminBundle\EventListener;

use Gedmo\Loggable\LoggableListener;
use Doctrine\Common\EventArgs;

/**
 * Custom Loggable listener
 */
class CustomLoggableListener extends LoggableListener
{

    /**
     * Override original method, put custom code if needed.
     *
     * Looks for loggable objects being inserted or updated
     * for further processing
     *
     * @param EventArgs $eventArgs
     *
     * @return void
     */
    public function onFlush(EventArgs $eventArgs)
    {
        $ea = $this->getEventAdapter($eventArgs);
        $om = $ea->getObjectManager();
        $uow = $om->getUnitOfWork();

        foreach ($ea->getScheduledObjectInsertions($uow) as $object) {
            $this->createLogEntry(self::ACTION_CREATE, $object, $ea);
        }
        foreach ($ea->getScheduledObjectUpdates($uow) as $object) {
            $this->createLogEntry(self::ACTION_UPDATE, $object, $ea);
        }
        foreach ($ea->getScheduledObjectDeletions($uow) as $object) {
            $this->createLogEntry(self::ACTION_REMOVE, $object, $ea);
        }
    }
}
