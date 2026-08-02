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

use MODX\Revolution\Transport\modTransportPackage;
use xPDO\xPDO;

/**
 * Resolves transport paths from the package workspace and removes the zip / unpacked directory on disk.
 *
 * Path rules mirror modTransportPackage::getTransport (workspace packages/ + source basename). If the
 * package has no related workspace row, cleanup falls back to core_path packages/ — that is intentional
 * for filesystem removal only; getTransport itself does not load a transport without a workspace.
 *
 * Expects $this->modx (modX) from Processor.
 */
trait TransportPackageFilesystemTrait
{
    /**
     * Absolute paths to the .transport.zip and unpacked transport directory for this package row.
     *
     * Unpacked folder name follows xPDOTransport: basename of archive without .transport.zip suffix.
     *
     * @return array{transportZip: string, transportDir: string}
     */
    protected function resolveTransportPaths(modTransportPackage $package): array
    {
        $workspace = $package->getOne('Workspace');
        if ($workspace !== null) {
            $base = rtrim($workspace->get('path'), '/') . '/packages/';
        } else {
            /* No workspace: still attempt cleanup under core packages dir (orphan / legacy rows). */
            $base = rtrim((string)$this->modx->getOption('core_path', null, ''), '/') . '/packages/';
        }

        $source = $package->get('source');
        if ($source === null || $source === '') {
            $source = $package->get('signature') . '.transport.zip';
        }
        $zipName = basename($source);
        $transportZip = $base . $zipName;
        $dirName = basename($zipName, '.transport.zip');
        $transportDir = $base . $dirName . '/';

        return [
            'transportZip' => $transportZip,
            'transportDir' => $transportDir,
        ];
    }

    /**
     * Remove the transport package archive
     *
     * Public for backward compatibility with callers that invoked these helpers on Purge/Remove.
     */
    public function removeTransportZip(string $transportZip): void
    {
        $this->modx->log(xPDO::LOG_LEVEL_INFO, $this->modx->lexicon('package_remove_info_tzip_start'));
        if (!file_exists($transportZip)) {
            $this->modx->log(xPDO::LOG_LEVEL_ERROR, $this->modx->lexicon('package_remove_err_tzip_nf'));
        } elseif (!@unlink($transportZip)) {
            $this->modx->log(xPDO::LOG_LEVEL_ERROR, $this->modx->lexicon('package_remove_err_tzip'));
        } else {
            $this->modx->log(xPDO::LOG_LEVEL_INFO, $this->modx->lexicon('package_remove_info_tzip'));
        }
    }

    /**
     * Remove the transport package directory
     *
     * Public for backward compatibility with callers that invoked these helpers on Purge/Remove.
     */
    public function removeTransportDirectory(string $transportDir): void
    {
        /* Same pattern as clearCache(): ensure cacheManager exists before deleteTree. */
        $this->modx->getCacheManager();
        $this->modx->log(xPDO::LOG_LEVEL_INFO, $this->modx->lexicon('package_remove_info_tdir_start'));
        if (!is_dir($transportDir)) {
            $this->modx->log(xPDO::LOG_LEVEL_ERROR, $this->modx->lexicon('package_remove_err_tdir_nf'));
        } elseif (!$this->modx->cacheManager->deleteTree($transportDir, true, false, [])) {
            $this->modx->log(xPDO::LOG_LEVEL_ERROR, $this->modx->lexicon('package_remove_err_tdir'));
        } else {
            $this->modx->log(xPDO::LOG_LEVEL_INFO, $this->modx->lexicon('package_remove_info_tdir'));
        }
    }
}
