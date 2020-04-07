<?php
/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

namespace MODX\Revolution\Processors\Workspace\Packages;

use MODX\Revolution\Processors\Processor;
use MODX\Revolution\modX;
use MODX\Revolution\Transport\modTransportPackage;
use MODX\Revolution\Transport\modTransportProvider;
use SimpleXMLElement;

/**
 * Update a package from its provider.
 * @param string $signature The signature of the package.
 * @package MODX\Revolution\Processors\Workspace\Packages
 */
class CheckForUpdates extends Processor
{
    /** @var modTransportPackage $package */
    public $package;

    /** @var modTransportProvider $provider */
    public $provider;

    /** @var string $packageSignature */
    public $packageSignature = '';

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
        $signature = $this->getProperty('signature');
        $this->package = $this->modx->getObject(modTransportPackage::class, $signature);
        if ($this->package === null) {
            $msg = $this->modx->lexicon('package_err_nf');
            $this->modx->log(modX::LOG_LEVEL_ERROR, $msg);
            return $msg;
        }
        $this->package->parseSignature();
        if ($this->package->provider !== 0) { /* if package has a provider */
            $this->provider = $this->package->getOne('Provider');
            if ($this->provider === null) {
                $msg = $this->modx->lexicon('provider_err_nf');
                $this->modx->log(modX::LOG_LEVEL_ERROR, $msg);
                return $msg;
            }
        } else {
            /* if no provider, indicate it is up to date */
            $msg = $this->modx->lexicon('package_err_uptodate', ['signature' => $this->package->get('signature')]);
            $this->modx->log(modX::LOG_LEVEL_INFO, $msg);
            return $msg;
        }

        return parent::initialize();
    }

    /**
     * @return array|mixed|string
     */
    public function process()
    {
        $this->modx->log(modX::LOG_LEVEL_INFO,
            $this->modx->lexicon('package_update_info_provider_scan', ['provider' => $this->provider->get('name')]));

        $packages = $this->provider->latest($this->getProperty('signature'));
        if (is_string($packages)) {
            return $this->failure($packages);
        }

        /* if no newer packages were found */
        if (count($packages) < 1) {
            $msg = $this->modx->lexicon('package_err_uptodate', ['signature' => $this->package->get('signature')]);
            $this->modx->log(modX::LOG_LEVEL_INFO, $msg);
            return $this->failure($msg);
        }

        $list = [];
        /** @var SimpleXMLElement $package */
        foreach ($packages as $package) {
            $packageArray = [
                'id' => (string)$package['id'],
                'changelog' => (string)$package['changelog'],
                'package' => (string)$package['package'],
                'version' => (string)$package['version'],
                'release' => (string)$package['release'],
                'signature' => (string)$package['signature'],
                'location' => (string)$package['location'],
                'info' => ((string)$package['location']) . '::' . ((string)$package['signature']),
            ];
            $list[] = $packageArray;
        }

        return $this->success('', $list);
    }
}
