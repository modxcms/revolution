<?php
require_once (dirname(__FILE__).'/update.class.php');
/**
 *
 * @param $data A JSON array of data to update from.
 *
 * @package modx
 * @subpackage processors.resource
 */
class modResourceUpdateFromGridProcessor extends modResourceUpdateProcessor {
    public $classKey = 'modResource';
    public $languageTopics = array('resource');
    public $permission = 'save_document';
    public $objectType = 'resource';
    public $beforeSaveEvent = 'OnBeforeDocFormSave';
    public $afterSaveEvent = 'OnDocFormSave';

    /** @var modResource $object */
    public $object;
    /** @var modUser $lockedUser */
    public $lockedUser;
    
    public function initialize() {
        $data = $this->getProperty('data');
        if (empty($data)) return $this->modx->lexicon('invalid_data');
        $data = $this->modx->fromJSON($data);
        if (empty($data)) return $this->modx->lexicon('invalid_data');
        $this->setProperties($data);
        $this->unsetProperty('data');
        $this->setProperty('clearCache',true);
        return parent::initialize();
    }
}
return 'modResourceUpdateFromGridProcessor';