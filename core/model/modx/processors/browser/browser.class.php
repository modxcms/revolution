<?php

/**
 * Abstract class for Update Element processors. To be extended for each derivative element type.
 *
 * @abstract
 * @package modx
 * @subpackage processors.element
 */
abstract class modBrowserProcessor extends modProcessor
{
    /** @var modMediaSource $source */
    public $source;
    /** @var string $permission A required user permission */
    public $permission = '';
    /** @var string $policy A required media source policy */
    public $policy = '';
    /** @var array $languageTopics An array of language topics to load */
    public $languageTopics = [];


    /**
     * @return bool
     */
    public function checkPermissions()
    {
        return !empty($this->permission)
            ? $this->modx->hasPermission($this->permission)
            : true;
    }


    /**
     * @return array
     */
    public function getLanguageTopics()
    {
        return $this->languageTopics;
    }


    /**
     * @return bool|null|string
     */
    public function initialize()
    {
        if (!$this->getSource()) {
            return $this->modx->lexicon('permission_denied');
        }
        if ($this->policy && !$this->source->checkPolicy($this->policy)) {
            return $this->modx->lexicon('permission_denied');
        }

        return true;
    }


    /**
     * Get the active Source
     *
     * @return modMediaSource|bool
     */
    public function getSource()
    {
        $source = $this->getProperty('source', 1);
        $this->modx->loadClass('sources.modMediaSource');
        $this->source = modMediaSource::getDefaultSource($this->modx, $source);
        if (!$this->source->getWorkingContext()) {
            return $this->modx->lexicon('permission_denied');
        }
        $this->source->setRequestProperties($this->getProperties());
        if (!$this->source->initialize()) {
            return false;
        }

        return $this->source;
    }

    /**
     * Get the list of protected directories
     *
     * @return array
     */
    public function getProtectedPathDirectories() {
        $protectedDirectories = array(
            MODX_ASSETS_PATH,
            MODX_BASE_PATH,
            MODX_CONNECTORS_PATH,
            MODX_CORE_PATH,
            MODX_MANAGER_PATH,
            MODX_PROCESSORS_PATH,
            XPDO_CORE_PATH,
        );
        return $protectedDirectories;
    }


    /**
     * @param $response
     *
     * @return array|string
     */
    public function handleResponse($response)
    {
        if (empty($response)) {
            $errors = $this->source->getErrors();
            if (count($errors) > 1) {
                foreach ($errors as $key => $message) {
                    $this->modx->error->addField($key, $message);
                }

                return $this->failure();
            } else {
                return $this->failure(array_shift($errors));
            }
        }

        return $this->success();
    }


    /**
     * @param $file
     *
     * @return string
     */
    public function sanitize($file)
    {
        $file = rawurldecode($file);
        $file = strip_tags($file);
        $file = preg_replace('#^[.\/]+#u', '', $file);
        $file = strip_tags($file);

        return $file;
    }
}
