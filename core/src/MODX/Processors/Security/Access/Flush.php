<?php

namespace MODX\Processors\Security\Access;

use MODX\Processors\modProcessor;

/**
 * Flushes permissions for the logged in user.
 *
 * @package modx
 * @subpackage processors.security.access
 */
class Flush extends modProcessor
{
    public function getLanguageTopics()
    {
        return ['topmenu'];
    }


    public function checkPermissions()
    {
        return $this->modx->hasPermission('access_permissions');
    }


    public function process()
    {
        if (!$this->modx->cacheManager->flushPermissions()) {
            return $this->failure($this->modx->lexicon('flush_sessions_err'));
        }

        return $this->success();
    }
}
