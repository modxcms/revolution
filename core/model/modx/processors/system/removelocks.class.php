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
 * Removes locks on all objects
 *
 * @package modx
 * @subpackage processors.system
 */
class modSystemRemoveLocksProcessor extends modProcessor {
    public function checkPermissions() {
        return $this->modx->hasPermission('remove_locks');
    }
    public function process() {
        /** @var modRegistry $registry */
        $registry = $this->modx->getService('registry', 'registry.modRegistry');
        if ($registry) {
            $registry->addRegister('locks', 'registry.modDbRegister', array('directory' => 'locks'));
            $registry->locks->connect();
            $registry->locks->subscribe('/resource/');
            $registry->locks->read(array('remove_read' => true, 'poll_limit' => 1, 'msg_limit' => 1000));
        }

        return $this->success();
    }
}
return 'modSystemRemoveLocksProcessor';
