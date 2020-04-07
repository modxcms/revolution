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
 * Purge old package versions
 * @param string $package_name The name of the package, could be set to * to purge all old packages
 * @package MODX\Revolution\Processors\Workspace\Packages
 */
class Purge extends Processor
{
    /** @var modTransportPackage[] $package */
    public $packages;

    public $packageName;

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
     * @return array|bool|string
     */
    public function initialize()
    {
        $this->setDefaultProperties(['package' => '']);

        $this->packageName = $this->getProperty('packagename');
        if (empty($this->packageName)) {
            $this->modx->log(xPDO::LOG_LEVEL_ERROR, $this->modx->lexicon('packagename_err_ns'));
            return $this->failure(xPDO::LOG_LEVEL_ERROR, $this->modx->lexicon('packagename_err_ns'));
        }

        $c = $this->modx->newQuery(modTransportPackage::class);
        $c->select('package_name');
        $c->groupby('package_name');
        if ($this->packageName !== '*') {
            $c->where([
                'package_name' => $this->packageName,
            ]);
        }
        $this->packages = $this->modx->getIterator(modTransportPackage::class, $c);

        if (empty($this->packages)) {
            $this->modx->log(xPDO::LOG_LEVEL_ERROR, $this->modx->lexicon('packagename_err_nf'));
            return $this->failure(xPDO::LOG_LEVEL_ERROR, $this->modx->lexicon('packagename_err_nf'));
        }

        return true;
    }

    /**
     * Return a failure message from the processor.
     * @param string $msg
     * @param mixed $object
     * @return array|string
     */
    public function failure($msg = '', $object = null)
    {
        $this->modx->log(modX::LOG_LEVEL_INFO, 'COMPLETED');
        sleep(2);
        return $this->modx->error->failure($msg, $object);
    }

    /**
     * @return array
     */
    public function process()
    {
        foreach ($this->packages as $package) {
            $c = $this->modx->newQuery(modTransportPackage::class, [
                'package_name' => $package->get('package_name'),
            ]);
            $c->where(['installed:!=' => '0000-00-00 00:00:00']);
            $c->sortby('installed', 'desc');
            $c->limit(1000, 1);
            $purgedPackages = $this->modx->getIterator(modTransportPackage::class, $c);
            foreach ($purgedPackages as $purgedPackage) {
                $this->removePackage($purgedPackage);
            }
        }
        $this->clearCache();

        return $this->cleanup();
    }

    /**
     * Remove the package
     * @param modTransportPackage $package
     * @return void
     */
    public function removePackage($package)
    {
        $this->modx->log(xPDO::LOG_LEVEL_INFO,
            $this->modx->lexicon('packages_purge_info_gpurge', ['signature' => $package->signature]));

        $transportZip = $this->modx->getOption('core_path') . 'packages/' . $package->signature . '.transport.zip';
        $transportDir = $this->modx->getOption('core_path') . 'packages/' . $package->signature . '/';
        if (file_exists($transportZip) && file_exists($transportDir)) {
            /* remove transport package */
            if ($package->remove() === false) {
                $this->modx->log(xPDO::LOG_LEVEL_ERROR,
                    $this->modx->lexicon('package_err_remove', ['signature' => $package->getPrimaryKey()]));
                $this->failure($this->modx->lexicon('package_err_remove', ['signature' => $package->getPrimaryKey()]));
                return;
            }
        } else {
            /* for some reason the files were removed, so just remove the DB object instead */
            $package->remove();
        }

        $this->removeTransportZip($transportZip);
        $this->removeTransportDirectory($transportDir);

        $this->modx->invokeEvent('OnPackageRemove', [
            'package' => $package,
        ]);
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
    }

    /**
     * Cleanup and return the result
     * @return array
     */
    public function cleanup()
    {
        if ($this->packageName === '*') {
            $this->modx->log(modX::LOG_LEVEL_WARN, $this->modx->lexicon('packages_purge_info_success'));
        } else {
            $this->modx->log(modX::LOG_LEVEL_WARN, $this->modx->lexicon('package_versions_purge_info_success'));
        }
        sleep(2);

        return $this->success();
    }

    /**
     * Return a success message from the processor.
     * @param string $msg
     * @param mixed $object
     * @return array|string
     */
    public function success($msg = '', $object = null)
    {
        $this->modx->log(modX::LOG_LEVEL_INFO, 'COMPLETED');
        sleep(2);
        return $this->modx->error->success($msg, $object);
    }

    /**
     * Log manager action
     */
    public function logManagerAction()
    {
        $this->modx->logManagerAction('packages_purge', modTransportPackage::class, $this->packageName);
    }
}
