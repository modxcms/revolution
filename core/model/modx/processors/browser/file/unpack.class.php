<?php

/**
 * Unpacks archives, currently only zip
 *
 * @package modx
 * @subpackage processors.system.filesys.file
 */
class modUnpackProcessor extends modProcessor
{
    /** @var modMediaSource|modFileMediaSource $source */
    public $source;


    /**
     * @return bool
     */
    public function checkPermissions()
    {
        return $this->modx->hasPermission('file_unpack');
    }


    /**
     * @return array
     */
    public function getLanguageTopics()
    {
        return ['file'];
    }


    /**
     * @return bool
     */
    public function initialize()
    {
        $this->properties = $this->getProperties();

        return true;
    }


    /**
     * @return array|bool|mixed|string
     */
    public function process()
    {
        $source = $this->getSource();
        if ($source !== true) {
            return $source;
        }

        if (!$this->source->checkPolicy('view')) {
            return $this->failure($this->modx->lexicon('permission_denied'));
        }

        return $this->unpack();
    }


    /**
     * @return array|string
     */
    public function unpack()
    {
        try {
            if ($data = $this->source->getMetadata($this->getProperty('file'))) {
                $base = $this->source->getBasePath();
                $target = explode(DIRECTORY_SEPARATOR, trim($data['path'], DIRECTORY_SEPARATOR));
                array_pop($target);
                $target = implode(DIRECTORY_SEPARATOR, $target);

                /** @noinspection PhpParamsInspection */
                if ($archive = new \xPDO\Compression\xPDOZip($this->modx, $base . $data['path'])) {
                    if (!$archive->unpack($this->source->postfixSlash($base . $target))) {
                        return $this->failure($this->modx->lexicon('file_err_unzip'));
                    }
                }
            } else {
                return $this->failure($this->modx->lexicon('file_err_open') . $this->getProperty('file'));
            }
        } catch (Exception $e) {
            return $this->failure($e->getMessage());
        }

        return $this->success($this->modx->lexicon('file_unzip'));
    }


    /**
     * @return boolean|string
     */
    public function getSource()
    {
        $source = $this->getProperty('source', 1);
        /** @var modMediaSource $source */
        $this->modx->loadClass('sources.modMediaSource');
        $this->source = modMediaSource::getDefaultSource($this->modx, $source);
        if (!$this->source->getWorkingContext()) {
            return $this->modx->lexicon('permission_denied');
        }
        $this->source->setRequestProperties($this->getProperties());

        return $this->source->initialize();
    }
}

return 'modUnpackProcessor';
