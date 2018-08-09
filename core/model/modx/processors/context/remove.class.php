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
 * Removes a context
 *
 * @param string $key The key of the context. Cannot be mgr or web.
 *
 * @package modx
 * @subpackage processors.context
 */
class modContextRemoveProcessor extends modObjectRemoveProcessor {
    public $classKey = 'modContext';
    public $languageTopics = array('context');
    public $permission = 'delete_context';
    public $objectType = 'context';
    public $primaryKeyField = 'key';

    public function beforeRemove() {
        /* prevent removing of mgr/web contexts */
        if ($this->object->get('key') == 'web' || $this->object->get('key') == 'mgr') {
            return $this->modx->lexicon('permission_denied');
        }
        return true;
    }

    public function afterRemove() {
        /* Retrieve all resources from this context. */
        $resources = $this->modx->getIterator('modResource',array(
            'context_key' => $this->object->get('key'),
        ));

        $resourceIds = array();
        foreach ($resources as $resource) {
            $resourceIds[] = $resource->get('id');
        }

        /* Remove content values.*/
        $this->modx->removeCollection('modTemplateVarResource',array(
            'contentid:IN' => $resourceIds,
        ));

        /* Remove resources. */
        $this->modx->removeCollection('modResource',array(
            'context_key' => $this->object->get('key'),
        ));

        return true;
    }

    public function cleanup() {
        $this->modx->cacheManager->refresh();
    }
}
return 'modContextRemoveProcessor';
