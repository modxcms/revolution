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
 * Release a lock on a resource
 */
class Release extends Processor
{
    public function checkPermissions()
    {
        return $this->modx->hasPermission('view');
    }

    public function process()
    {
        $released = false;
        $id = $this->getProperty('id');
        $id = intval($id);

        if (!empty($id)) {
            /** @var modResource $resource */
            $resource = $this->modx->getObject(modResource::class, $id);
            if ($resource) {
                $released = $resource->removeLock($this->modx->user->get('id'));
            }
        }

        if (!$released) {
            $this->failure();
        }

        return $this->success();
    }
}
