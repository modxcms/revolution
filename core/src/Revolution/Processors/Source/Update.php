<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

namespace MODX\Revolution\Processors\Source;

use MODX\Revolution\Processors\Model\UpdateProcessor;
use MODX\Revolution\Sources\modAccessMediaSource;
use MODX\Revolution\Sources\modMediaSource;

/**
 * Updates a Media Source
 * @param integer $id The ID of the Source
 * @package MODX\Revolution\Processors\Source
 */
class Update extends UpdateProcessor
{
    public $classKey = modMediaSource::class;
    public $languageTopics = ['source'];
    public $permission = 'source_save';
    public $objectType = 'source';
    public $beforeSaveEvent = 'OnMediaSourceBeforeFormSave';
    public $afterSaveEvent = 'OnMediaSourceFormSave';

    /** @var modMediaSource $object */
    public $object;

    /**
     * @return bool
     * @throws \xPDO\xPDOException
     */
    public function beforeSave()
    {
        $this->setSourceProperties();
        return parent::beforeSave();
    }

    /**
     * Sets the properties on the source
     * @return void
     * @throws \xPDO\xPDOException
     */
    public function setSourceProperties()
    {
        $properties = $this->getProperty('properties');
        if (!empty($properties)) {
            $properties = is_array($properties) ? $properties : $this->modx->fromJSON($properties);
            $this->object->setProperties($properties);
        }
    }

    /**
     * @return bool
     * @throws \xPDO\xPDOException
     */
    public function afterSave()
    {
        $this->setAccess();
        return parent::afterSave();
    }

    /**
     * Sets access permissions for the source
     * @return void
     * @throws \xPDO\xPDOException
     */
    public function setAccess()
    {
        $access = $this->getProperty('access');
        if ($access !== null) {
            $acls = $this->modx->getCollection(modAccessMediaSource::class, [
                'target' => $this->object->get('id'),
            ]);
            /** @var modAccessMediaSource $acl */
            foreach ($acls as $acl) {
                $acl->remove();
            }

            $access = is_array($access) ? $access : $this->modx->fromJSON($access);
            if (!empty($access) && is_array($access)) {
                foreach ($access as $data) {
                    /** @var modAccessMediaSource $acl */
                    $acl = $this->modx->newObject(modAccessMediaSource::class);
                    $acl->fromArray([
                        'target' => $this->object->get('id'),
                        'principal_class' => $data['principal_class'],
                        'principal' => $data['principal'],
                        'authority' => $data['authority'],
                        'policy' => $data['policy'],
                        'context_key' => $data['context_key'],
                    ], '', true, true);
                    $acl->save();
                }
            }
        }
    }
}
