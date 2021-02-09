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

/**
 * Uninstall a package
 * @param string $signature The signature of the package.
 * @package MODX\Revolution\Processors\Workspace\Packages
 */
class Uninstall extends Processor
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
        $this->setDefaultProperties([
            'signature' => '',
        ]);
        $this->modx->log(modX::LOG_LEVEL_INFO,
            $this->modx->lexicon('package_uninstall_info_find', ['signature' => $this->getProperty('signature')]));
        $signature = $this->getProperty('signature');
        if (empty($signature)) {
            $this->modx->log(modX::LOG_LEVEL_INFO, 'COMPLETED');
            return $this->modx->lexicon('package_err_ns');
        }
        $this->package = $this->modx->getObject(modTransportPackage::class, $signature);
        if ($this->package === null) {
            $this->modx->log(modX::LOG_LEVEL_INFO, 'COMPLETED');
            return $this->modx->lexicon('package_err_nfs', [
                'signature' => $signature,
            ]);
        }
        return true;
    }

    /**
     * @return array|mixed|string
     */
    public function process()
    {
        $this->modx->log(modX::LOG_LEVEL_INFO, $this->modx->lexicon('package_uninstall_info_prep'));

        /* uninstall package */
        $options = [
            xPDOTransport::PREEXISTING_MODE => $this->getProperty('preexisting_mode'),
        ];

        if ($this->package->uninstall($options) === false) {
            return $this->failure(sprintf($this->modx->lexicon('package_err_uninstall'), ['signature' => $this->package->get('signature')], $this->package->getPrimaryKey()));
        }

        $this->modx->log(modX::LOG_LEVEL_WARN,
            $this->modx->lexicon('package_uninstall_info_success', ['signature' => $this->package->get('signature')]));
        sleep(2);
        $this->modx->log(modX::LOG_LEVEL_INFO, 'COMPLETED');

        $this->logManagerAction();

        $this->modx->invokeEvent('OnPackageUninstall', [
            'package' => $this->package,
        ]);

        $this->clearCache();

        return $this->success();
    }

    public function logManagerAction()
    {
        $this->modx->logManagerAction('package_uninstall', modTransportPackage::class, $this->package->get('id'));
    }

    public function clearCache()
    {
        $this->modx->cacheManager->refresh([
            $this->modx->getOption('cache_packages_key', null, 'packages') => [],
        ]);
        $this->modx->cacheManager->refresh();

    }
}
