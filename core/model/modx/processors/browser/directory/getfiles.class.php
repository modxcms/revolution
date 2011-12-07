<?php
/**
 * Gets all files in a directory
 *
 * @param string $dir The directory to browse
 * @param boolean $prependPath (optional) If true, will prepend rb_base_dir to
 * the final path
 * @param boolean $prependUrl (optional) If true, will prepend rb_base_url to
 * the final url
 *
 * @var modX $modx
 * @var array $scriptProperties
 * @var modProcessor $this
 *
 * @package modx
 * @subpackage processors.browser.directory
 */
class modBrowserFolderGetFilesProcessor extends modProcessor {
    /** @var modMediaSource|modFileMediaSource $source */
    public $source;
    public function checkPermissions() {
        return $this->modx->hasPermission('file_list');
    }

    public function getLanguageTopics() {
        return array('file');
    }

    public function initialize() {
        $this->setDefaultProperties(array(
            'dir' => '',
        ));
        if ($this->getProperty('dir') == 'root') {
            $this->setProperty('dir','');
        }
        return true;
    }

    public function process() {
        if (!$this->getSource()) {
            return $this->failure($this->modx->lexicon('permission_denied'));
        }
        $this->source->setRequestProperties($this->getProperties());
        $this->source->initialize();
        $this->modx->setPlaceholder('mediasource.res_id',$this->getProperty('res_id'));
        $this->autoFolder();
        $list = $this->source->getObjectsInContainer($this->getProperty('dir'));
        return $this->outputArray($list);
    }

    /**
     * Get the active Source
     * @return modMediaSource|boolean
     */
    public function getSource() {
        $this->modx->loadClass('sources.modMediaSource');
        $this->source = modMediaSource::getDefaultSource($this->modx,$this->getProperty('source'));
        if (empty($this->source) || !$this->source->getWorkingContext()) {
            return false;
        }
        return $this->source;
    }
    
    public function autoFolder()
    {
        if ($this->getProperty('autoCreateFolder') == 'true'){
            $bases = $this->source->getBases();
            $targetDir = $bases['pathAbsolute'];
            $cacheManager = $this->modx->getCacheManager();
            /* if directory doesnt exist, create it */
            if (!file_exists($targetDir) || !is_dir($targetDir)) {
                if (!$cacheManager->writeTree($targetDir)) {
                    $this->modx->log(modX::LOG_LEVEL_ERROR, '[MIGX] Could not create directory: ' . $targetDir);
                    //return $modx->error->failure('Could not create directory: ' . $targetDir);
                }
            }
        }
        return true;
    }    
}
return 'modBrowserFolderGetFilesProcessor';