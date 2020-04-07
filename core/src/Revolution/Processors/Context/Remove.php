<?php
/*
 * This file is part of the MODX Revolution package.
 *
 * Copyright (c) MODX, LLC
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace MODX\Revolution\Processors\Context;


use MODX\Revolution\modContext;
use MODX\Revolution\Processors\Model\RemoveProcessor;
use MODX\Revolution\modResource;
use MODX\Revolution\modTemplateVarResource;

/**
 * Removes a context
 *
 * @property string $key The key of the context. Cannot be mgr or web.
 *
 * @package MODX\Revolution\Processors\Context
 */
class Remove extends RemoveProcessor
{
    public $classKey = modContext::class;
    public $languageTopics = ['context'];
    public $permission = 'delete_context';
    public $objectType = 'context';
    public $primaryKeyField = 'key';

    public function beforeRemove()
    {
        /* prevent removing of mgr/web contexts */
        if ($this->object->get('key') == 'web' || $this->object->get('key') == 'mgr') {
            return $this->modx->lexicon('permission_denied');
        }

        return true;
    }

    public function afterRemove()
    {
        /* Retrieve all resources from this context. */
        $resources = $this->modx->getIterator(modResource::class, [
            'context_key' => $this->object->get('key'),
        ]);

        $resourceIds = [];
        foreach ($resources as $resource) {
            $resourceIds[] = $resource->get('id');
        }

        /* Remove content values.*/
        $this->modx->removeCollection(modTemplateVarResource::class, [
            'contentid:IN' => $resourceIds,
        ]);

        /* Remove resources. */
        $this->modx->removeCollection(modResource::class, [
            'context_key' => $this->object->get('key'),
        ]);

        return true;
    }

    public function cleanup()
    {
        $this->modx->cacheManager->refresh();
    }
}
