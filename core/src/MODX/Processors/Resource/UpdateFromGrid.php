<?php

namespace MODX\Processors\Resource;

use MODX\modResource;
use MODX\modUser;

/**
 *
 * @param string $data A JSON array of data to update from.
 *
 * @package modx
 * @subpackage processors.resource
 */
class UpdateFromGrid extends Update
{
    public $classKey = 'modResource';
    public $languageTopics = ['resource'];
    public $permission = 'save_document';
    public $objectType = 'resource';
    public $beforeSaveEvent = 'OnBeforeDocFormSave';
    public $afterSaveEvent = 'OnDocFormSave';

    /** @var modResource $object */
    public $object;
    /** @var modUser $lockedUser */
    public $lockedUser;


    public function initialize()
    {
        $data = $this->getProperty('data');
        if (empty($data)) return $this->modx->lexicon('invalid_data');
        $data = json_decode($data, true);
        if (empty($data)) return $this->modx->lexicon('invalid_data');
        $this->setProperties($data);
        $this->unsetProperty('data');
        $this->setProperty('clearCache', true);

        return parent::initialize();
    }
}