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
 * Flushes permissions for the logged in user.
 *
 * @package modx
 * @subpackage processors.security.access
 */
class modFlushPermissionsProcessor extends modProcessor {
    public function getLanguageTopics() {
        return array('topmenu');
    }

    public function checkPermissions() {
        return $this->modx->hasPermission('access_permissions');
    }

    public function process() {
        if (!$this->modx->cacheManager->flushPermissions()) {
            return $this->failure($this->modx->lexicon('flush_sessions_err'));
        }
        return $this->success();
    }
}
return 'modFlushPermissionsProcessor';
