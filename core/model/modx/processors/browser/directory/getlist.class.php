<?php
/**
 * Get a list of directories and files, sorting them first by folder/file and
 * then alphanumerically.
 *
 * @param string $id The path to grab a list from
 * @param boolean $prependPath (optional) If true, will prepend rb_base_dir to
 * the final path
 * @param boolean $hideFiles (optional) If true, will not display files.
 * Defaults to false.
 *
 * @var modX $modx
 * @var array $scriptProperties
 * @var modProcessor $this
 *
 * @package modx
 * @subpackage processors.browser.directory
 */
class modBrowserFolderGetListProcessor extends modProcessor {
    /** @var modMediaSource|modFileMediaSource $source */
    public $source;

    public function getLanguageTopics() {
        return array('file');
    }

    public function initialize() {
        $this->setDefaultProperties(array(
            'id' => '',
        ));
        $dir = $this->getProperty('id');
        if (empty($dir) || $dir == 'root') {
            $this->setProperty('id','');
        } else if (strpos($dir, 'n_') === 0) {
            $dir = substr($dir, 2);
        }
        $this->setProperty('dir',$dir);
        return true;
    }

    public function process() {
        if (!$this->getSource()) {
            return $this->modx->toJSON(array());
        }
        if (!$this->source->checkPolicy('list')) {
            return $this->modx->toJSON(array());
        }
        $this->source->setRequestProperties($this->getProperties());
        $this->source->initialize();

        $list = $this->source->getContainerList($this->getProperty('dir'));
        return $this->modx->toJSON($list);
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
}
return 'modBrowserFolderGetListProcessor';