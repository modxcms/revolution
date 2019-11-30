<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

namespace MODX\Revolution\Processors\Workspace\Packages\Rest;

use MODX\Revolution\Processors\Processor;
use MODX\Revolution\Transport\modTransportProvider;

/**
 * @package MODX\Revolution\Processors\Workspace\Packages\Rest
 */
class GetInfo extends Processor
{
    /** @var modTransportProvider $provider */
    public $provider;

    /**
     * @return bool
     */
    public function checkPermissions()
    {
        return $this->modx->hasPermission('packages');
    }

    /**
     * @return array
     */
    public function getLanguageTopics()
    {
        return ['workspace'];
    }

    /**
     * @return array|mixed|string
     */
    public function process()
    {
        if (!$this->loadProvider()) {
            return [];
        }

        $info = $this->provider->stats();
        if (empty($info)) {
            return $this->failure($this->modx->lexicon('provider_err_connect'));
        }

        return $this->success('', $info);
    }

    /**
     * Load the provider
     * @return boolean
     */
    public function loadProvider()
    {
        $loaded = false;
        $provider = $this->getProperty('provider', false);
        if (!empty($provider)) {
            $this->provider = $this->modx->getObject(modTransportProvider::class, $provider);
            if ($this->provider !== null) {
                $loaded = true;
            }
        }
        return $loaded;
    }
}
