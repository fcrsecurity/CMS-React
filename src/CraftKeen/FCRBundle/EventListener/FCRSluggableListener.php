<?php

namespace CraftKeen\FCRBundle\EventListener;

use Doctrine\Common\EventArgs;
use Gedmo\Exception\InvalidArgumentException;
use Gedmo\Sluggable\Handler\SlugHandlerInterface;
use Gedmo\Sluggable\Handler\SlugHandlerWithUniqueCallbackInterface;
use Gedmo\Sluggable\Mapping\Event\SluggableAdapter;
use Doctrine\Common\Persistence\ObjectManager;
use Gedmo\Tool\Wrapper\AbstractWrapper;
use Gedmo\Sluggable\SluggableListener;

class FCRSluggableListener extends SluggableListener
{
    /**
     * The power exponent to jump
     * the slug unique number by tens.
     *
     * @var integer
     */
    private $exponent = 0;

    /**
     * Transliteration callback for slugs
     *
     * @var callable
     */
    private $transliterator = ['Gedmo\Sluggable\Util\Urlizer', 'transliterate'];

    /**
     * Urlize callback for slugs
     *
     * @var callable
     */
    private $urlizer = ['Gedmo\Sluggable\Util\Urlizer', 'urlize'];

    /**
     * List of inserted slugs for each object class.
     * This is needed in case there are identical slug
     * composition in number of persisted objects
     * during the same flush
     *
     * @var array
     */
    private $persisted = [];

    /**
     * List of initialized slug handlers
     *
     * @var array
     */
    private $handlers = [];

    /**
     * List of filters which are manipulated when slugs are generated
     *
     * @var array
     */
    private $managedFilters = [];

    /**
     * Generate slug on objects being updated during flush
     * if they require changing
     *
     * @param EventArgs $args
     *
     * @return void
     */
    public function onFlush(EventArgs $args)
    {
        $this->persisted = [];
        $ea = $this->getEventAdapter($args);
        $om = $ea->getObjectManager();
        $uow = $om->getUnitOfWork();

        $this->manageFiltersBeforeGeneration($om);

        // process all objects being inserted, using scheduled insertions instead
        // of prePersist in case if record will be changed before flushing this will
        // ensure correct result. No additional overhead is encountered
        foreach ($ea->getScheduledObjectInsertions($uow) as $object) {
            $meta = $om->getClassMetadata(get_class($object));
            if ($this->getConfiguration($om, $meta->name)) {
                // generate first to exclude this object from similar persisted slugs result
                $this->generateSlug($ea, $object);
                $this->persisted[$ea->getRootObjectClass($meta)][] = $object;
            }
        }
        // we use onFlush and not preUpdate event to let other
        // event listeners be nested together
        foreach ($ea->getScheduledObjectUpdates($uow) as $object) {
            $meta = $om->getClassMetadata(get_class($object));
            if ($this->getConfiguration($om, $meta->name) && !$uow->isScheduledForInsert($object)) {
                $this->generateSlug($ea, $object);
                $this->persisted[$ea->getRootObjectClass($meta)][] = $object;
            }
        }

        $this->manageFiltersAfterGeneration($om);

        AbstractWrapper::clear();
    }

    /**
     * Get the slug handler instance by $class name
     *
     * @param string $class
     *
     * @return SlugHandlerInterface
     */
    private function getHandler($class)
    {
        if (!isset($this->handlers[$class])) {
            $this->handlers[$class] = new $class($this);
        }

        return $this->handlers[$class];
    }

    /**
     * Creates the slug for object being flushed
     *
     * @param SluggableAdapter $ea
     * @param object $object
     *
     * @return void
     */
    private function generateSlug(SluggableAdapter $ea, $object)
    {
        $om = $ea->getObjectManager();
        $meta = $om->getClassMetadata(get_class($object));
        $uow = $om->getUnitOfWork();
        $changeSet = $ea->getObjectChangeSet($uow, $object);
        $isInsert = $uow->isScheduledForInsert($object);
        $config = $this->getConfiguration($om, $meta->name);

        foreach ($config['slugs'] as $slugField => $options) {
            $hasHandlers = count($options['handlers']);
            $options['useObjectClass'] = $config['useObjectClass'];
            // collect the slug from fields
            $slug = $meta->getReflectionProperty($slugField)->getValue($object);

            // if slug should not be updated, skip it
            if (!$options['updatable'] && !$isInsert && (!isset($changeSet[$slugField]) || $slug === '__id__')) {
                if ('live' == $object->getStatus()) {
                    $slug = str_replace($options['suffix'], '', $slug);

                    if ($options['unique'] && null !== $slug) {
                        $slug = $this->makeUniqueSlug($ea, $object, $slug, false, $options);
                    }
                    $object->setSlug($slug);
                }
                continue;
            }
            // must fetch the old slug from changeset, since $object holds the new version
            $oldSlug = isset($changeSet[$slugField]) ? $changeSet[$slugField][0] : $slug;
            $needToChangeSlug = false;

            // if slug is null, regenerate it, or needs an update
            if (null === $slug || $slug === '__id__' || !isset($changeSet[$slugField])) {
                $slug = '';

                foreach ($options['fields'] as $sluggableField) {
                    if (isset($changeSet[$sluggableField]) || isset($changeSet[$slugField])) {
                        $needToChangeSlug = true;
                    }
                    $value = $meta->getReflectionProperty($sluggableField)->getValue($object);
                    $slug .= ($value instanceof \DateTime) ? $value->format($options['dateFormat']) : $value;
                    $slug .= ' ';
                }
                // trim generated slug as it will have unnecessary trailing space
                $slug = trim($slug);
            } else {
                // slug was set manually
                $needToChangeSlug = true;
            }
            // notify slug handlers --> onChangeDecision
            if ($hasHandlers) {
                foreach ($options['handlers'] as $class => $handlerOptions) {
                    $this->getHandler($class)->onChangeDecision($ea, $options, $object, $slug, $needToChangeSlug);
                }
            }

            // if slug is changed, do further processing
            if ($needToChangeSlug) {
                $mapping = $meta->getFieldMapping($slugField);
                // notify slug handlers --> postSlugBuild
                $urlized = false;

                if ($hasHandlers) {
                    foreach ($options['handlers'] as $class => $handlerOptions) {
                        $this->getHandler($class)->postSlugBuild($ea, $options, $object, $slug);
                        if ($this->getHandler($class)->handlesUrlization()) {
                            $urlized = true;
                        }
                    }
                }

                // build the slug
                // Step 1: transliteration, changing 北京 to 'Bei Jing'
                $slug = call_user_func_array(
                    $this->transliterator,
                    [$slug, $options['separator'], $object]
                );

                // Step 2: urlization (replace spaces by '-' etc...)
                if (!$urlized) {
                    $slug = call_user_func_array(
                        $this->urlizer,
                        [$slug, $options['separator'], $object]
                    );
                }

                // add suffix
                if (stripos($slug, $options['suffix']) === false) {
                    $slug = $slug . $options['suffix'];
                }

                // add prefix
                if (stripos($slug, $options['prefix']) === false) {
                    $slug = $options['prefix'] . $slug;
                }

                // Step 3: stylize the slug
                switch ($options['style']) {
                    case 'camel':
                        $quotedSeparator = preg_quote($options['separator']);
                        $slug = preg_replace_callback('/^[a-z]|' . $quotedSeparator . '[a-z]/smi', function ($m) {
                            return strtoupper($m[0]);
                        }, $slug);
                        break;

                    case 'lower':
                        if (function_exists('mb_strtolower')) {
                            $slug = mb_strtolower($slug);
                        } else {
                            $slug = strtolower($slug);
                        }
                        break;

                    case 'upper':
                        if (function_exists('mb_strtoupper')) {
                            $slug = mb_strtoupper($slug);
                        } else {
                            $slug = strtoupper($slug);
                        }
                        break;

                    default:
                        // leave it as is
                        break;
                }

                // cut slug if exceeded in length
                if (isset($mapping['length']) && strlen($slug) > $mapping['length']) {
                    $slug = substr($slug, 0, $mapping['length']);
                }

                if (isset($mapping['nullable']) && $mapping['nullable'] && strlen($slug) === 0) {
                    $slug = null;
                }

                // notify slug handlers --> beforeMakingUnique
                if ($hasHandlers) {
                    foreach ($options['handlers'] as $class => $handlerOptions) {
                        $handler = $this->getHandler($class);
                        if ($handler instanceof SlugHandlerWithUniqueCallbackInterface) {
                            $handler->beforeMakingUnique($ea, $options, $object, $slug);
                        }
                    }
                }

                // Delete suffix in slug for published object
                if ('live' == $object->getStatus()) {
                    $slug = str_replace($options['suffix'], '', $slug);
                }

                // make unique slug if requested
                if ($options['unique'] && null !== $slug) {
                    $this->exponent = 0;
                    $slug = $this->makeUniqueSlug($ea, $object, $slug, false, $options);
                }

                // notify slug handlers --> onSlugCompletion
                if ($hasHandlers) {
                    foreach ($options['handlers'] as $class => $handlerOptions) {
                        $this->getHandler($class)->onSlugCompletion($ea, $options, $object, $slug);
                    }
                }

                // set the final slug
                $meta->getReflectionProperty($slugField)->setValue($object, $slug);
                // recompute changeset
                $ea->recomputeSingleObjectChangeSet($uow, $meta, $object);
                // overwrite changeset (to set old value)
                $uow->propertyChanged($object, $slugField, $oldSlug, $slug);
            }
        }
    }

    /**
     * Generates the unique slug
     *
     * @param SluggableAdapter $ea
     * @param object $object
     * @param string $preferredSlug
     * @param boolean $recursing
     * @param array $config [$slugField]
     *
     * @return string - unique slug
     */
    private function makeUniqueSlug(
        SluggableAdapter $ea,
        $object,
        $preferredSlug,
        $recursing = false,
        $config = []
    )
    {
        $om = $ea->getObjectManager();
        $meta = $om->getClassMetadata(get_class($object));
        $similarPersisted = [];

        // extract unique base
        $base = false;

        if ($config['unique'] && isset($config['unique_base'])) {
            $base = $meta->getReflectionProperty($config['unique_base'])->getValue($object);
        }

        // collect similar persisted slugs during this flush
        if (isset($this->persisted[$class = $ea->getRootObjectClass($meta)])) {
            foreach ($this->persisted[$class] as $obj) {
                if ($base !== false && $meta->getReflectionProperty($config['unique_base'])->getValue($obj) !== $base) {
                    continue; // if unique_base field is not the same, do not take slug as similar
                }
                $slug = $meta->getReflectionProperty($config['slug'])->getValue($obj);
                $quotedPreferredSlug = preg_quote($preferredSlug);
                if (preg_match("@^{$quotedPreferredSlug}.*@smi", $slug)) {
                    $similarPersisted[] = [$config['slug'] => $slug];
                }
            }
        }

        // load similar slugs
        $result = array_merge((array)$ea->getSimilarSlugs($object, $meta, $config, $preferredSlug), $similarPersisted);

        if (count((array)$result) <= 0) {
            $objects = $om->getRepository(get_class($object))->findBy(['slug' => $preferredSlug]);
            foreach ($objects as $item) {
                $result[] = [$config['slug'] => $item->getSlug()];
            }
        }

        // leave only right slugs
        if (!$recursing) {
            // filter similar slugs
            $quotedSeparator = preg_quote($config['separator']);
            $quotedPreferredSlug = preg_quote($preferredSlug);
            foreach ($result as $key => $similar) {
                if (!preg_match("@{$quotedPreferredSlug}($|{$quotedSeparator}[\d]+$)@smi", $similar[$config['slug']])) {
                    unset($result[$key]);
                }
            }
        }

        if ($result) {
            $generatedSlug = $preferredSlug;
            $sameSlugs = [];

            foreach ((array)$result as $list) {
                $noSuffix = stripos($list[$config['slug']], $config['suffix']) === false;
                $isExistedSlug = $list[$config['slug']] == $generatedSlug;
                if (($object->getStatus() == 'live' && $noSuffix) ||
                    ($object->getStatus() != 'live' && $isExistedSlug)
                ) {
                    $sameSlugs[] = $list[$config['slug']];
                }
            }

            $i = pow(10, $this->exponent);
            if ($recursing || in_array($generatedSlug, $sameSlugs)) {
                do {
                    if (stripos($generatedSlug, $config['suffix']) === false) {
                        $generatedSlug = $preferredSlug . $config['separator'] . $i++;
                    } else {
                        $preferredSlug = str_replace($config['suffix'], '', $preferredSlug);
                        $generatedSlug = $preferredSlug . $config['separator'] . $i++ . $config['suffix'];
                    }
                } while (in_array($generatedSlug, $sameSlugs));
            }

            $mapping = $meta->getFieldMapping($config['slug']);
            if (isset($mapping['length']) && strlen($generatedSlug) > $mapping['length']) {
                $generatedSlug = substr(
                    $generatedSlug,
                    0,
                    $mapping['length'] - (strlen($i) + strlen($config['separator']))
                );
                $this->exponent = strlen($i) - 1;
                if (substr($generatedSlug, -strlen($config['separator'])) == $config['separator']) {
                    $generatedSlug = substr($generatedSlug, 0, strlen($generatedSlug) - strlen($config['separator']));
                }
                $generatedSlug = $this->makeUniqueSlug($ea, $object, $generatedSlug, true, $config);
            }
            $preferredSlug = $generatedSlug;
        }

        return $preferredSlug;
    }

    /**
     * @param ObjectManager $om
     */
    private function manageFiltersBeforeGeneration(ObjectManager $om)
    {
        $collection = $this->getFilterCollectionFromObjectManager($om);

        $enabledFilters = array_keys($collection->getEnabledFilters());

        // set each managed filter to desired status
        foreach ($this->managedFilters as $name => &$config) {
            $enabled = in_array($name, $enabledFilters);
            $config['previouslyEnabled'] = $enabled;

            if ($config['disabled']) {
                if ($enabled) {
                    $collection->disable($name);
                }
            } else {
                $collection->enable($name);
            }
        }
    }

    /**
     * @param ObjectManager $om
     */
    private function manageFiltersAfterGeneration(ObjectManager $om)
    {
        $collection = $this->getFilterCollectionFromObjectManager($om);

        // Restore managed filters to their original status
        foreach ($this->managedFilters as $name => &$config) {
            if ($config['previouslyEnabled'] === true) {
                $collection->enable($name);
            }

            unset($config['previouslyEnabled']);
        }
    }

    /**
     * Retrieves a FilterCollection instance from the given ObjectManager.
     *
     * @param ObjectManager $om
     *
     * @throws InvalidArgumentException
     *
     * @return mixed
     */
    private function getFilterCollectionFromObjectManager(ObjectManager $om)
    {
        if (is_callable([$om, 'getFilters'])) {
            return $om->getFilters();
        } elseif (is_callable([$om, 'getFilterCollection'])) {
            return $om->getFilterCollection();
        }

        throw new InvalidArgumentException("ObjectManager does not support filters");
    }
}
