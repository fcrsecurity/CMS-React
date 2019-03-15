<?php
/**
 * Created by PhpStorm.
 * User: andreykopkin
 * Date: 22.11.17
 * Time: 13:46
 */

namespace CraftKeen\BrochureBuilderBundle\FileManager;

use FM\ElfinderBundle\Bridge\ElFinderBridge;

/**
 * Custom bridge to implement custom operations
 *
 * Class FileManagerBridge
 * @package CraftKeen\BrochureBuilderBundle\FileManager
 */
class FileManagerBridge extends ElFinderBridge
{
    /**
     * Overrride original constructor
     *
     * FileManagerBridge constructor.
     * @param $opts
     */
    public function __construct($opts) {
        parent::__construct($opts);
        $this->commands['meta'] = array('targets' => TRUE, 'meta' => TRUE, 'aoda' => TRUE);
        $this->commands['search'] = array('q' => true, 'mimes' => false, 'target' => false, 'meta' => false);
    }

    /**
     * Impement meta saving
     *
     * @param $args
     * @return array
     */
    protected function meta($args) {
        $targets = is_array($args['targets']) ? $args['targets'] : array();
        $result  = ['updated' => []];
        $meta    = $args['meta'];
        $aoda    = $args['aoda'];

        foreach ($targets as $target) {
            $volume = $this->volume($target);
            $file   = $volume ? $volume->file($target) : false;
            $disabled = $volume ? $volume->commandDisabled('meta') : false;
            $status = (!$disabled && $volume) ? $volume->saveCustomMeta($target, $meta, $aoda) : false;

            if (!$file) {
                $result['error'] = $this->error(self::ERROR_FILE_NOT_FOUND);
                return $result;
            } elseif ($disabled) {
                $result['error'] = $this->error(self::ERROR_PERM_DENIED, $file['name']);
                return $result;
            } else {
                array_push($result['updated'], $file['hash']);
            }
        }
        return $result;
    }

    /**
     * @inheritdoc
     **/
    protected function search($args) {
        $q      = trim($args['q']);
        $mimes  = !empty($args['mimes']) && is_array($args['mimes']) ? $args['mimes'] : array();
        $meta   = !empty($args['meta']) ? $args['meta'] : null;
        $target = !empty($args['target'])? $args['target'] : null;
        $result = array();
        $errors = array();

        if ($target) {
            if ($volume = $this->volume($target)) {
                $result = $volume->search($q, $mimes, $target, $meta);
                $errors = array_merge($errors, $volume->error());
            }
        } else {
            foreach ($this->volumes as $volume) {
                $result = array_merge($result, $volume->search($q, $mimes, null, $meta));
                $errors = array_merge($errors, $volume->error());
            }
        }

        $result = array('files' => $result);
        if ($errors) {
            $result['warning'] = $errors;
        }
        return $result;
    }
}
