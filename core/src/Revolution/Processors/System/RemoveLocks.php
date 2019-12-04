<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

namespace MODX\Revolution\Processors\System;

use MODX\Revolution\Processors\Processor;
use MODX\Revolution\Registry\modDbRegister;
use MODX\Revolution\Registry\modRegistry;

/**
 * Removes locks on all objects
 * @package MODX\Revolution\Processors\System
 */
class RemoveLocks extends Processor
{
    /**
     * @return bool
     */
    public function checkPermissions()
    {
        return $this->modx->hasPermission('remove_locks');
    }

    /**
     * @return array|mixed|string
     */
    public function process()
    {
        /** @var modRegistry $registry */
        $registry = $this->modx->getService('registry', modRegistry::class);
        if ($registry) {
            $registry->addRegister('locks', modDbRegister::class, ['directory' => 'locks']);
            $registry->locks->connect();
            $registry->locks->subscribe('/resource/');
            $registry->locks->read(['remove_read' => true, 'poll_limit' => 1, 'msg_limit' => 1000]);
        }

        return $this->success();
    }
}
