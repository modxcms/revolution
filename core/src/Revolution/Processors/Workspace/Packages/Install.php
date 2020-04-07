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
use xPDO\Transport\xPDOTransport;
use xPDO\xPDO;

/**
 * Install a package
 * @param string $signature The signature of the package.
 * @package MODX\Revolution\Processors\Workspace\Packages
 */
class Install extends Processor
{
    /** @var modTransportPackage $package */
    public $package;

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
        $this->setDefaultProperties(['signature' => '']);
        $this->modx->log(modX::LOG_LEVEL_INFO,
            $this->modx->lexicon('package_install_info_start', ['signature' => $this->getProperty('signature')]));
        $signature = $this->getProperty('signature');
        if (empty($signature)) {
            $this->modx->log(modX::LOG_LEVEL_INFO, 'COMPLETED');
            return $this->modx->lexicon('package_err_ns');
        }
        $this->package = $this->modx->getObject(modTransportPackage::class, $signature);
        if ($this->package === null) {
            $this->modx->log(modX::LOG_LEVEL_INFO, 'COMPLETED');
            return $this->modx->lexicon('package_err_nf');
        }
        return true;
    }

    /**
     * @return array|mixed|string
     */
    public function process()
    {
        $this->modx->log(xPDO::LOG_LEVEL_INFO, $this->modx->lexicon('package_install_info_found'));

        $installed = $this->package->install($this->getProperties());

        $this->clearCache();

        if (!$installed) {
            $msg = $this->modx->lexicon('package_err_install', ['signature' => $this->package->get('signature')]);
            $this->modx->log(modX::LOG_LEVEL_ERROR, $msg);
            $this->modx->log(modX::LOG_LEVEL_INFO, 'COMPLETED');
            return $this->failure($msg);
        }

        $msg = $this->modx->lexicon('package_install_info_success',
            ['signature' => $this->package->get('signature')]);
        $this->modx->log(modX::LOG_LEVEL_WARN, $msg);
        $this->modx->log(modX::LOG_LEVEL_INFO, 'COMPLETED');

        $this->modx->invokeEvent('OnPackageInstall', [
            'package' => $this->package,
            'action' => $this->package->previousVersionInstalled() ? xPDOTransport::ACTION_UPGRADE : xPDOTransport::ACTION_INSTALL,
        ]);

        return $this->success($msg);
    }

    public function clearCache()
    {
        $this->modx->cacheManager->refresh([
            $this->modx->getOption('cache_packages_key', null, 'packages') => [],
        ]);
        $this->modx->cacheManager->refresh();
    }
}
