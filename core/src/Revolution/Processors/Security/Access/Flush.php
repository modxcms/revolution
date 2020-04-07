<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

namespace MODX\Revolution\Processors\Security\Access;

use MODX\Revolution\Processors\Processor;

/**
 * Flushes permissions for the logged in user.
 * @package MODX\Revolution\Processors\Security\Access
 */
class Flush extends Processor
{
    /**
     * @return array
     */
    public function getLanguageTopics()
    {
        return ['topmenu'];
    }

    /**
     * @return bool
     */
    public function checkPermissions()
    {
        return $this->modx->hasPermission('access_permissions');
    }

    /**
     * @return array|mixed|string
     */
    public function process()
    {
        if (!$this->modx->cacheManager->flushPermissions()) {
            return $this->failure($this->modx->lexicon('flush_sessions_err'));
        }

        return $this->success();
    }
}
