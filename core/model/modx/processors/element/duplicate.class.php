<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

/**
 * Abstract class for Duplicate Element processors. To be extended for each derivative element type.
 *
 * @abstract
 * @package modx
 * @subpackage processors.element
 */
class modElementDuplicateProcessor extends modObjectDuplicateProcessor {
    public function cleanup() {
        return $this->success('',$this->newObject->get(array('id', 'name', 'description', 'category', 'locked')));
    }

    public function afterSave() {
        if ($this->getProperty('clearCache')) {
            $this->modx->cacheManager->refresh();
        }
        return parent::afterSave();
    }
}
