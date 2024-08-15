<?php
/*
 * This file is part of the MODX Revolution package.
 *
 * Copyright (c) MODX, LLC
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace MODX\Revolution\Processors\Security;

use MODX\Revolution\Processors\Processor;
use MODX\Revolution\modSession;
use MODX\Revolution\modSessionHandler;

/**
 * Flush all sessions
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

    public function checkPermissions()
    {
        return $this->modx->hasPermission('flush_sessions');
    }

    public function process()
    {
        $sessionHandler = $this->modx->services->get('session_handler');
        if (!method_exists($sessionHandler, 'flushSessions')) {
            return $this->failure($this->modx->lexicon('flush_sessions_not_supported'));
        }
        $flushed = call_user_func_array([$sessionHandler, 'flushSessions'], [&$this->modx]);
        if (!$flushed) {
            return $this->failure($this->modx->lexicon('flush_sessions_err'));
        }
        return $this->success();
    }
}
