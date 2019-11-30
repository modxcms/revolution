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
use MODX\Revolution\modResource;
use MODX\Revolution\modX;

/**
 * Regenerate the system's Resource URIs in the database
 * @package MODX\Revolution\Processors\System
 */
class RefreshUris extends Processor
{
    /**
     * @return bool
     */
    public function checkPermissions()
    {
        return $this->modx->hasPermission('empty_cache');
    }

    /**
     * @return array|mixed|string
     */
    public function process()
    {
        $this->modx->call(modResource::class, 'refreshURIs', [&$this->modx]);
        $result = true; // refreshURIs void response
        $output = $this->modx->lexicon('refresh_' . ($result ? 'success' : 'failure'));
        $this->modx->log(modX::LOG_LEVEL_INFO, $output);

        return $this->success($output);
    }
}
