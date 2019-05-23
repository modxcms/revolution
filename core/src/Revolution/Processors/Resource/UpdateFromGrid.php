<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

namespace MODX\Revolution\Processors\Resource;


use MODX\Revolution\modResource;
use MODX\Revolution\modUser;

/**
 *
 * @param $data A JSON array of data to update from.
 */
class UpdateFromGrid extends Update
{
    public $classKey = modResource::class;
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
        $data = $this->modx->fromJSON($data);
        if (empty($data)) return $this->modx->lexicon('invalid_data');
        $this->setProperties($data);
        $this->unsetProperty('data');
        $this->setProperty('clearCache', true);
        return parent::initialize();
    }
}
