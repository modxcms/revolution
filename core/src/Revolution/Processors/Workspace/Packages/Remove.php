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
use xPDO\xPDO;

/**
 * Remove a package
 * @param string $signature The signature of the package.
 * @param boolean $force (optional) If true, will remove the package even if
 * uninstall fails. Defaults to false.
 * @package MODX\Revolution\Processors\Workspace\Packages
 */
class Remove extends Processor
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
            'force' => false,
        ]);
        if (in_array($this->getProperty('force'), ['true', 1, true], true)) {
            $this->setProperty('force', true);
        }

        $signature = $this->getProperty('signature');
        if (empty($signature)) {
            return $this->modx->lexicon('package_err_ns');
        }
        $this->package = $this->modx->getObject(modTransportPackage::class, $signature);
        if ($this->package === null) {
            return $this->modx->lexicon('package_err_nf');
        }
        return true;
    }

    /**
     * @return array|mixed|string
     */
    public function process()
    {
        $this->modx->log(xPDO::LOG_LEVEL_INFO, $this->modx->lexicon('package_remove_info_gpack'));

        $transportZip = $this->modx->getOption('core_path') . 'packages/' . $this->package->signature . '.transport.zip';
        $transportDir = $this->modx->getOption('core_path') . 'packages/' . $this->package->signature . '/';
        if (file_exists($transportZip) && file_exists($transportDir)) {
            /* remove transport package */
            if ($this->package->removePackage($this->getProperty('force')) === false) {
                $packageSignature = $this->package->getPrimaryKey();
                $this->modx->log(xPDO::LOG_LEVEL_ERROR,
                    $this->modx->lexicon('package_err_remove', ['signature' => $packageSignature]));
                return $this->failure($this->modx->lexicon('package_err_remove', ['signature' => $packageSignature]));
            }
        } else {
            /* for some reason the files were removed, so just remove the DB object instead */
            $this->package->remove();
        }

        $this->clearCache();
        $this->removeTransportZip($transportZip);
        $this->removeTransportDirectory($transportDir);

        return $this->cleanup();
    }

    /**
     * Empty the site cache
     * @return void
     */
    public function clearCache()
    {
        $this->modx->getCacheManager();
        $this->modx->cacheManager->refresh([
            $this->modx->getOption('cache_packages_key', null, 'packages') => [],
        ]);
        $this->modx->cacheManager->refresh();
        sleep(2);
    }

    /**
     * Remove the transport package archive
     * @param string $transportZip
     * @return void
     */
    public function removeTransportZip($transportZip)
    {
        $this->modx->log(xPDO::LOG_LEVEL_INFO, $this->modx->lexicon('package_remove_info_tzip_start'));
        if (!file_exists($transportZip)) {
            $this->modx->log(xPDO::LOG_LEVEL_ERROR, $this->modx->lexicon('package_remove_err_tzip_nf'));
        } else if (!@unlink($transportZip)) {
            $this->modx->log(xPDO::LOG_LEVEL_ERROR, $this->modx->lexicon('package_remove_err_tzip'));
        } else {
            $this->modx->log(xPDO::LOG_LEVEL_INFO, $this->modx->lexicon('package_remove_info_tzip'));
        }
    }

    /**
     * Remove the transport package directory
     * @param string $transportDir
     * @return void
     */
    public function removeTransportDirectory($transportDir)
    {
        $this->modx->log(xPDO::LOG_LEVEL_INFO, $this->modx->lexicon('package_remove_info_tdir_start'));
        if (!file_exists($transportDir)) {
            $this->modx->log(xPDO::LOG_LEVEL_ERROR, $this->modx->lexicon('package_remove_err_tdir_nf'));
        } else if (!$this->modx->cacheManager->deleteTree($transportDir, true, false, [])) {
            $this->modx->log(xPDO::LOG_LEVEL_ERROR, $this->modx->lexicon('package_remove_err_tdir'));
        } else {
            $this->modx->log(xPDO::LOG_LEVEL_INFO, $this->modx->lexicon('package_remove_info_tdir'));
        }
    }

    /**
     * Cleanup and return the result
     * @return array
     */
    public function cleanup()
    {
        $this->modx->log(modX::LOG_LEVEL_WARN, $this->modx->lexicon('package_remove_info_success'));
        sleep(2);
        $this->modx->log(modX::LOG_LEVEL_INFO, 'COMPLETED');

        $this->modx->invokeEvent('OnPackageRemove', [
            'package' => $this->package,
        ]);

        return $this->success();
    }
}
