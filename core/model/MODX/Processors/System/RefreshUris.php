<?php

namespace MODX\Processors\System;

use MODX\MODX;
use MODX\Processors\modProcessor;

/**
 * Regenerate the system's Resource URIs in the database
 *
 * @package modx
 * @subpackage system
 */
class RefreshUris extends modProcessor
{
    public function checkPermissions()
    {
        return $this->modx->hasPermission('empty_cache');
    }


    public function process()
    {
        $this->modx->call('modResource', 'refreshURIs', [&$this->modx]);
        $result = true; // refreshURIs void response
        $output = $this->modx->lexicon('refresh_' . ($result ? 'success' : 'failure'));
        $this->modx->log(MODX::LOG_LEVEL_INFO, $output);

        return $this->success($output);
    }
}