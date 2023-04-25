<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

namespace MODX\Revolution\Processors\Resource\Locks;


use MODX\Revolution\Processors\Processor;
use MODX\Revolution\modResource;

/**
 * Steal a lock on a resource
 */
class Steal extends Processor
{
    public function checkPermissions()
    {
        return $this->modx->hasPermission('steal_locks');
    }

    public function process()
    {
        $scriptProperties = $this->getProperties();
        $stolen = false;
        if (!empty($scriptProperties['id'])) {
            /** @var modResource $resource */
            $resource = $this->modx->getObject(modResource::class, intval($scriptProperties['id']));
            if ($resource && $resource->checkPolicy('steal_lock')) {
                $lock = $resource->getLock($this->modx->user->get('id'));
                if ($lock > 0 && $lock != $this->modx->user->get('id')) {
                    $resource->removeLock($lock);
                    $stolen = $resource->addLock($this->modx->user->get('id'));
                }
            }
        }
        if ($stolen !== true) return $this->failure($stolen);

        return $this->success();
    }
}
