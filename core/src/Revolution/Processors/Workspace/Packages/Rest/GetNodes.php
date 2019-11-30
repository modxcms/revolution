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
class GetNodes extends Processor
{
    /** @var modTransportProvider $provider */
    public $provider;

    public $nodeType = 'root';

    public $nodeKey = '';

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
     * @return bool|string|null
     */
    public function initialize()
    {
        $provider = $this->getProperty('provider', false);
        if (empty($provider)) {
            return $this->modx->lexicon('provider_err_ns');
        }
        $this->provider = $this->modx->getObject(modTransportProvider::class, $provider);
        if ($this->provider === null) {
            return $this->modx->lexicon('provider_err_nf');
        }

        return true;
    }

    /**
     * @return array|mixed|string
     */
    public function process()
    {
        /* get client */
        /* load appropriate processor */
        $id = $this->getProperty('id', 'n_root_0');
        $ar = explode('_', $id);
        $this->nodeType = !empty($ar[1]) ? $ar[1] : 'root';
        $this->nodeKey = !empty($ar[2]) ? $ar[2] : null;
        switch ($this->nodeType) {
            case 'repository':
                $list = $this->getCategories();
                break;
            case 'tag':
                $list = [];
                break;
            case 'root':
            default:
                $list = $this->getRepositories();
                break;
        }
        if (!is_array($list)) {
            return $this->failure($list);
        }
        return $this->toJSON($list);
    }

    /**
     * @return array|string|null
     */
    public function getCategories()
    {
        return $this->provider->categories($this->nodeKey);
    }

    /**
     * @return array
     */
    public function getRepositories()
    {
        return $this->provider->repositories();
    }
}
