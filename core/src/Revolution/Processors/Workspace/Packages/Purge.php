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
    use TransportPackageFilesystemTrait;

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
            $c->where(['installed:IS NOT' => null]);
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
     * Remove the package (DB row via removePackage/remove, then delete transport files on disk).
     *
     * Uses workspace-relative paths like modTransportPackage::getTransport. Purge uses force=true so
     * older installed rows are dropped without blocking the rest of the run.
     *
     * If both archive and unpacked dir exist but neither removePackage nor remove() can drop the DB row,
     * filesystem cleanup is skipped (same as legacy purge) and OnPackageRemove is not fired.
     */
    public function removePackage(modTransportPackage $package): void
    {
        $this->modx->log(
            xPDO::LOG_LEVEL_INFO,
            $this->modx->lexicon('packages_purge_info_gpurge', ['signature' => $package->get('signature')])
        );

        $paths = $this->resolveTransportPaths($package);
        $transportZip = $paths['transportZip'];
        $transportDir = $paths['transportDir'];

        $zipExists = file_exists($transportZip);
        $dirExists = is_dir($transportDir);

        $result = $this->attemptRemovePackageFromDatabase($package, $zipExists, $dirExists);

        if ($result['skipFilesystemCleanup']) {
            return;
        }

        $this->removeTransportZip($transportZip);
        $this->removeTransportDirectory($transportDir);

        $this->modx->invokeEvent('OnPackageRemove', [
            'package' => $package,
        ]);
    }

    /**
     * When both zip and unpacked dir exist: removePackage(true), then remove() if needed; if both fail,
     * skip disk cleanup (legacy purge behaviour).
     *
     * When either artifact is missing: only remove(). If that fails, disk cleanup still runs so orphan
     * transport files under resolved paths are removed even though the DB row may remain.
     *
     * @return array{skipFilesystemCleanup: bool}
     */
    private function attemptRemovePackageFromDatabase(
        modTransportPackage $package,
        bool $zipExists,
        bool $dirExists
    ): array {
        if ($zipExists && $dirExists) {
            if ($package->removePackage(true) !== false) {
                return ['skipFilesystemCleanup' => false];
            }
            $this->logPackageRemoveError($package);
            if ($package->remove() !== false) {
                return ['skipFilesystemCleanup' => false];
            }

            return ['skipFilesystemCleanup' => true];
        }

        if ($package->remove() !== false) {
            return ['skipFilesystemCleanup' => false];
        }
        $this->logPackageRemoveError($package);

        return ['skipFilesystemCleanup' => false];
    }

    private function logPackageRemoveError(modTransportPackage $package): void
    {
        $this->modx->log(
            xPDO::LOG_LEVEL_ERROR,
            $this->modx->lexicon('package_err_remove', ['signature' => $package->getPrimaryKey()])
        );
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
