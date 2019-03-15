<?php
/**
 * Created by PhpStorm.
 * User: andreykopkin
 * Date: 20.11.17
 * Time: 14:02
 */

namespace CraftKeen\BrochureBuilderBundle\FileManager;

use CraftKeen\BrochureBuilderBundle\Entity\FileManagerObject;
use CraftKeen\CMS\UserBundle\Entity\User;
use CraftKeen\FCRBundle\Entity\Property;
use FM\ElFinderPHP\Driver\ElFinderVolumeDriver;
use FM\ElFinderPHP\Driver\ElFinderVolumeLocalFileSystem;
use Doctrine\Bundle\DoctrineBundle\Registry;
use Symfony\Component\DependencyInjection\ContainerInterface;

class FileSystemDriver extends ElFinderVolumeLocalFileSystem
{

    const PROPERTY_IMAGES_DIRECTORY     = 'PropertyImages';
    const PROPERTY_TENANTS_DIRECTORY    = 'PropertyTenants';
    const LIFESTYLE_IMAGES_DIRECTORY    = 'LifestyleImages';
    const SITE_PLANS_DIRECTORY          = 'SitePlans';

    /**
     * @var string
     */
    protected $history;

    /**
     * @var string
     */
    protected $quarantine;

    /**
     * @var Registry
     */
    protected $doctrine;

    /**
     * @var User
     */
    protected $user;

    /**
     * @var string
     */
    protected $baseAppPath;

    /**
     * @var Property
     */
    protected $property = null;

    /**
     * @var ContainerInterface
     */
    protected $container = null;

    /**
     * Constructor
     * Extend options with required fields
     * @param Registry $doctrine
     * @param User $user
     * @param Property $property
     */
    public function __construct(Registry $doctrine, User $user, ContainerInterface $container, Property $property = null)
    {
        parent::__construct();
        $this->options['history'] = '.history';
        $this->doctrine = $doctrine;
        $this->user = $user;
        $this->container = $container;
        $this->baseAppPath = $this->container->get('kernel')->getRootDir();
        $this->property = $property;
    }

    /**
     * Configure after successfully mount.
     *
     * @return void
     **/
    public function configure()
    {
        parent::configure();
        // check history dir
        $this->history = '';
        if (!empty($this->options['history'])) {
            if (is_dir($this->options['history'])) {
                if (is_writable($this->options['history'])) {
                    $this->history = $this->options['history'];
                }
                $this->options['history'] = '';
            } else {
                $this->history = $this->_abspath($this->options['history']);
                if ((!is_dir($this->history) && !$this->_mkdir($this->root, $this->options['history'])) || !is_writable($this->history)) {
                    $this->options['history'] = $this->history = '';
                }
            }
        }

        if (!$this->property) {
            $this->_mkdir($this->root, self::LIFESTYLE_IMAGES_DIRECTORY);
        }
    }

    /**
     * Convert path to db format
     *
     * @param $path
     * @return string
     */
    private function _convertPathForEntity($path) {
        // Dont reduce path
        return $path;
    }

    /**
     * Create database entry for entity
     *
     * @param $path
     */
    private function _createDbEntry($path) {
        $explodedPath = explode(DIRECTORY_SEPARATOR, $path);
        if (is_file($path)) {
            $type = FileManagerObject::TYPE_FILE;
        } else {
            $type = FileManagerObject::TYPE_FOLDER;
        }
        $entity = new FileManagerObject();
        $entity->setParent($this->_getParent($this->_convertPathForEntity($path)));
        $entity->setOwner($this->user);
        $entity->setName(array_pop($explodedPath));
        $entity->setMime(ElFinderVolumeDriver::mimetypeInternalDetect($path));
        $entity->setPath($this->_convertPathForEntity($path));
        $entity->setType($type);
        $entity->setCreatedAt(new \DateTime());
        $this->doctrine->getEntityManager()->persist($entity);
        $this->doctrine->getEntityManager()->flush();
    }

    /**
     * Update database entry
     *
     * @param $path
     * @param array $updates
     */
    private function _updateDbEntry($path, $updates = []) {
        /** @var FileManagerObject $entity */
        $entity = $this->doctrine->getRepository(FileManagerObject::class)
                                 ->findOneByPath($this->_convertPathForEntity($path));
        foreach ($updates as $field => $value) {
            $method = 'set' . ucfirst($field);
            if (method_exists($entity, $method)) {
                $entity->{$method}($value);
            }

        }
        // update parent
        $explodedPath = explode(DIRECTORY_SEPARATOR, $entity->getPath());
        $entity->setName(array_pop($explodedPath));
        $entity->setParent($this->_getParent($entity->getPath()));
        $entity->setModifiedAt(new \DateTime());
        $this->doctrine->getEntityManager()->flush();

    }

    /**
     * Get parent entity for a path
     *
     * @param $entityPath
     * @return FileManagerObject|null
     */
    private function _getParent($entityPath) {
        $data = explode(DIRECTORY_SEPARATOR, $entityPath);
        array_pop($data);
        $parentPath = implode(DIRECTORY_SEPARATOR, $data);
        $parent = null;
        if($this->_convertPathForEntity($this->root) !== $parentPath) {
            $parent = $this->doctrine->getRepository(FileManagerObject::class)->findOneBy([
                'path'      => $parentPath,
                'status'    => FileManagerObject::STATUS_LIVE
            ]);
        }
        return $parent;
    }

    /**
     * Create file and return it's path or false on failed
     *
     * @param  string  $path  parent dir path
     * @param string  $name  new file name
     * @return string|bool
     **/
    protected function _mkfile($path, $name) {
        $result = parent::_mkfile($path, $name);
        if ($result) {
            $this->_createDbEntry($result);
        }
        return $result;
    }

    /**
     * Remove file
     *
     * @param  string  $path  file path
     * @return bool
     **/
    protected function _unlink($path) {
        $realPath = $this->_relpath($path);
        $targetPath = $this->_joinPath($this->history, $realPath);
        $this->_mkdir($this->history, $realPath);
        $fileName = date('YmdHis') . '-delete';
        $ret = parent::_move($path, $targetPath, $fileName); // call parent method without database mapping changes
        if ($ret) {
            $this->_updateDbEntry($path, ['status' => FileManagerObject::STATUS_DELETED]);
        }
        return $ret;
    }

    /**
     * Create dir and return created dir path or false on failed
     *
     * @param  string  $path  parent dir path
     * @param string  $name  new directory name
     * @return string|bool
     * @author Dmitry (dio) Levashov
     **/
    protected function _mkdir($path, $name) {
        $isHistory = ($this->history == $path || $this->history == $this->_joinPath($path, $name));
        $isQuarantine = ($this->quarantine == $path || $this->quarantine == $this->_joinPath($path, $name));
        $path = $this->_joinPath($path, $name);

        if (@mkdir($path, $this->options['dirMode'], true)) {
            clearstatcache();
            if (!$isHistory && !$isQuarantine) { //Don't create entry for history and quarantine
                $this->_createDbEntry($path);
            }
            return $path;
        }

        return false;
    }

    /**
     * Remove dir
     *
     * @param  string  $path  dir path
     * @return bool
     **/
    protected function _rmdir($path) {
        $result = parent::_rmdir($path);
        if ($result) {
            $this->_updateDbEntry($path, ['status' => FileManagerObject::STATUS_DELETED]);
        }
        return $result;
    }

    /**
     * Remove directory recursive on local file system
     *
     * @param string $dir Target dirctory path
     * @return boolean
     */
    public function rmdirRecursive($dir) {
        if (!is_link($dir) && is_dir($dir)) {
            @chmod($dir, 0777);
            foreach (array_diff(scandir($dir), ['.', '..']) as $file) {
                @set_time_limit(30);
                $path = $dir . DIRECTORY_SEPARATOR . $file;
                if (!is_link($dir) && is_dir($path)) {
                    $this->rmdirRecursive($path);
                } else {
                    $this->_unlink($path);
                }
            }
            return $this->_rmdir($dir);
        } else if (is_file($dir) || is_link($dir)) {
            @chmod($dir, 0666);
            return $this->_unlink($dir);
        }
        return false;
    }

    /**
     * Copy file into another file
     *
     * @param  string  $source     source file path
     * @param  string  $targetDir  target directory path
     * @param  string  $name       new file name
     * @return bool
     **/
    protected function _copy($source, $targetDir, $name) {
        $ret = parent::_copy($source, $targetDir, $name);
        if ($ret) {
            $this->_createDbEntry($this->_joinPath($targetDir, $name));
        }
        return $ret;
    }

    /**
     * Move file into another parent dir.
     * Return new file path or false.
     *
     * @param  string  $source  source file path
     * @param  string  $targetDir  target dir path
     * @param  string  $name    file name
     * @return string|bool
     **/
    protected function _move($source, $targetDir, $name) {
        $result = parent::_move($source, $targetDir, $name);
        if ($result) {
            $this->_recursiveUpdateEntity($source, $this->_joinPath($targetDir, $name));
        }
        return $result;
    }

    /**
     * Update targets in sub directories
     *
     * @param $source
     * @param $targetDir
     */
    private function _recursiveUpdateEntity($source, $targetDir) {
        $this->_updateDbEntry($source, ['path' => $this->_convertPathForEntity($targetDir)]);
        if (is_dir($targetDir)) {
            foreach (array_diff(scandir($targetDir), array('..', '.')) as $item) {
                $newSource = $source . DIRECTORY_SEPARATOR . $item;
                $newTarget = $targetDir . DIRECTORY_SEPARATOR . $item;
                $this->_updateDbEntry($newSource, ['path' => $this->_convertPathForEntity($newTarget)]);
                if (is_dir($item)) {
                    $this->_recursiveUpdateEntity($newSource, $newTarget);
                }
            }
        }
    }

    /**
     * Create new file and write into it from file pointer.
     * Return new file path or false on error.
     *
     * @param  resource  $fp   file pointer
     * @param  string    $dir  target dir path
     * @param  string    $name file name
     * @param  array     $stat file stat (required by some virtual fs)
     * @return bool|string
     **/
    protected function _save($fp, $dir, $name, $stat) {
        $result = parent::_save($fp, $dir, $name, $stat);
        if ($result) {
            $this->_createDbEntry($result);
        }
        return $result;
    }

    /**
     * Create archive and return its path
     *0
     * @param  string  $dir    target dir
     * @param  array   $files  files names list
     * @param  string  $name   archive name
     * @param  array   $arc    archiver options
     * @return string|bool
     **/
    protected function _archive($dir, $files, $name, $arc) {
        $result = $this->makeArchive($dir, $files, $name, $arc);
        if ($result) {
            $this->_createDbEntry($result);
        }
        return $result;
    }

    /**
     * Extract files from archive
     *
     * @param  string  $path  archive path
     * @param  array   $arc   archiver command and arguments (same as in $this->archivers)
     * @return true
     **/
    protected function _extract($path, $arc) {
        $result = parent::_extract($path, $arc);
        if ($result) {
            $this->_createDbEntry($result);
            foreach (array_diff(scandir($result), ['.', '..']) as $file) {
                $path = $result . DIRECTORY_SEPARATOR . $file;
                $this->_createDbEntry($path);
            }
        }
        return $result;
    }

    /**
     * @inheritdoc
     */
    protected function stat($path)
    {
        $result = parent::stat($path);
        $customMeta = '';
        $customAoda = '';
        $id = null;
        /** @var FileManagerObject $entity */
        $entity = $this->doctrine->getRepository(FileManagerObject::class)
                                 ->findOneByPath($this->_convertPathForEntity($path));
        if (null !== $entity) {
            $customMeta = $entity->getMetaData();
            $customAoda = $entity->getAodaData();
            $id = $entity->getId();
        }
        if (!empty($result)) {
            $result['customMeta'] = $customMeta;
            $result['customAoda'] = $customAoda;
            $result['idFile'] = $id;

            unset($result['alias']);
            if (isset($result['thash'])) {
                $absolutePath = $this->decode($result['thash']);
                $uploadPath = $this->container->getParameter('ckcms_library_path');
                $relativePath = substr($absolutePath, strlen($uploadPath));
                $result['resolvedLink'] = $this->container->getParameter('ckcms_library_url') . $relativePath;
                $result['resolvedPath'] = $absolutePath;
            }


            // Update stat data for Lifestyle Directory. Only admin can write
            if ($path == $this->root . DIRECTORY_SEPARATOR . self::LIFESTYLE_IMAGES_DIRECTORY
                && !$this->user->hasRole(User::ROLE_BROCHURE_ADMINISTRATOR)
                && !empty($result)) {
                $result['write'] = false;
            }

            // if (false !== strpos($path, $this->root . DIRECTORY_SEPARATOR . self::SITE_PLANS_DIRECTORY) && !empty($result)) {
            //     $result['locked'] = false;
            // }

            // Update stat data for own files. User can change only his own files (admin can change all)
            if ($entity
                && $this->user !== $entity->getOwner()
                && !$this->user->hasRole(User::ROLE_BROCHURE_ADMINISTRATOR)) {
                $result['write'] = false;
            }
        }
        return $result;
    }

    /**
     * Save metadata to object entity
     *
     * @param $dsthash
     * @param $meta
     * @return bool
     */
    public function saveCustomMeta($dsthash, $meta, $aoda) {
        /** @var FileManagerObject $entity */
        $entity = $this->doctrine->getRepository(FileManagerObject::class)
                                 ->findOneByPath($this->_convertPathForEntity($this->decode($dsthash)));
        if (null === $entity) {
            return false;
        }
        $entity->setMetaData($meta);
        $entity->setAodaData($aoda);
        $this->doctrine->getManager()->flush();
        return true;
    }

    /**
     * @inheritdoc
     */
    protected function doSearch($path, $q, $mimes, $meta = '') {
        if (!empty($meta)) {
            $entities = $this->doctrine->getRepository(FileManagerObject::class)->searchByMeta($meta);
            $result = [];
            foreach ($entities as $entity) {
                /** @var FileManagerObject $entity */
                $path = $entity->getPath();
                array_push($result, $this->stat($path));
            }
            return $result;
        } else {
            return parent::doSearch($path, $q, $mimes);
        }
    }

    /**
     * @inheritdoc
     **/
    public function search($q, $mimes, $hash = null, $meta = '') {
        $dir = null;
        if ($hash) {
            $dir = $this->decode($hash);
            $stat = $this->stat($dir);
            if (!$stat || $stat['mime'] !== 'directory' || !$stat['read']) {
                $q = '';
            }
        }
        if ($mimes && $this->onlyMimes) {
            $mimes = array_intersect($mimes, $this->onlyMimes);
            if (!$mimes) {
                $q = '';
            }
        }
        $this->searchStart = time();
        return ($q === '' || $this->commandDisabled('search'))
            ? array()
            : $this->doSearch(is_null($dir)? $this->root : $dir, $q, $mimes, $meta);
    }

    /**
     * @inheritdoc
     */
    public function resize($hash, $width, $height, $x, $y, $mode = 'resize', $bg = '', $degree = 0, $jpgQuality = null) {
        //Make history object
        $path = $this->decode($hash);
        $realPath = $this->_relpath($path);
        $targetPath = $this->_joinPath($this->history, $realPath);
        $this->_mkdir($this->history, $realPath);
        $fileName = date('YmdHis') . '-resize';
        parent::_copy($path, $targetPath, $fileName); // call parent method without database mapping changes

        //perform changes
        $result = parent::resize($hash, $width, $height, $x, $y, $mode, $bg, $degree, $jpgQuality);

        if (!$result) {
            //remove history entry
            parent::_rmdir($targetPath);
        }

        return $result;
    }

    /**
     * @inheritdoc
     **/
    protected function _filePutContents($path, $content) {
        if (false !== filter_var($content, FILTER_VALIDATE_URL)) {
            $content = urldecode($content);
            // reload data form url to raw content
            $pathArray = explode('/', $path);
            $contentArray = explode('/', $content);
            $fileName = end($contentArray);
            end($pathArray);
            $pathArray[key($pathArray)] = $fileName;
            $content = @file_get_contents(implode('/', $pathArray));
        }
        if (@file_put_contents($path, $content, LOCK_EX) !== false) {
            clearstatcache();
            return true;
        }
        return false;
    }
}
