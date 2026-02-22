<?php

/*
 * This file is part of MODX Revolution.
 *
 * Copyright (c) MODX, LLC. All Rights Reserved.
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 */

namespace MODX\Revolution\Processors\SoftwareUpdate;

use MODX\Revolution\modSessionHandler;
use MODX\Revolution\modX;
use Psr\Http\Client\ClientExceptionInterface;

/**
 * Downloads the MODX upgrade package from Sentinel, extracts it, copies files
 * into place, prepares setup, and returns the setup URL for redirect.
 *
 * @package MODX\Revolution\Processors\SoftwareUpdate
 */
class UpgradeCore extends Base
{
    public $permission = 'settings';
    public $languageTopics = ['setting'];

    /** @var string */
    private $tempDir;

    /** @var string */
    private $extractDir;

    /** @var string */
    private $archiveRoot;

    public function initialize()
    {
        if (function_exists('set_time_limit')) {
            @set_time_limit(0);
        }
        return parent::initialize();
    }

    public function checkPermissions()
    {
        if (parent::checkPermissions()) {
            return true;
        }
        $allowedGroups = $this->modx->getOption('core_upgrade_allowed_groups', null, 'Administrator');
        $groups = array_map('trim', explode(',', $allowedGroups));
        foreach ($groups as $group) {
            if ($this->modx->user->isMember($group)) {
                return true;
            }
        }
        return false;
    }

    public function process()
    {
        $downloadId = $this->getProperty('downloadId');
        if (empty($downloadId) || !$this->isValidDownloadId($downloadId)) {
            return $this->failure($this->modx->lexicon('invalid_download_id'));
        }

        $zipUrl = $this->getZipUrl($downloadId);
        if ($zipUrl === null) {
            return $this->failure($this->modx->lexicon('software_update_err_retrieve'));
        }

        $defaultTemp = $this->modx->getOption('core_path') . 'cache/upgrade/';
        $this->tempDir = rtrim(
            $this->modx->getOption('core_upgrade_temp_dir', null, $defaultTemp),
            DIRECTORY_SEPARATOR
        ) . DIRECTORY_SEPARATOR;
        if (!is_dir($this->tempDir)) {
            $this->modx->getCacheManager();
            if (!$this->modx->cacheManager->writeTree($this->tempDir)) {
                return $this->failure($this->modx->lexicon('software_update_err_temp_dir'));
            }
        }

        $zipPath = $this->downloadZip($zipUrl);
        if ($zipPath === null) {
            return $this->failure($this->modx->lexicon('software_update_err_download'));
        }

        $this->extractDir = $this->tempDir . 'extract' . DIRECTORY_SEPARATOR;
        if (!$this->extractZip($zipPath)) {
            $this->cleanup();
            return $this->failure($this->modx->lexicon('software_update_err_extract'));
        }

        $this->archiveRoot = $this->findArchiveRoot();
        if ($this->archiveRoot === null) {
            $this->cleanup();
            return $this->failure($this->modx->lexicon('software_update_err_archive_structure'));
        }

        if (!$this->copyFiles()) {
            $this->cleanup();
            return $this->failure($this->modx->lexicon('software_update_err_copy'));
        }

        if (!$this->prepareSetup()) {
            $this->cleanup();
            return $this->failure($this->modx->lexicon('software_update_err_prepare_setup'));
        }

        $this->cleanup();

        $basePath = rtrim($this->modx->getOption('base_path'), '/') . '/';
        $redirectUrl = $basePath . 'setup/index.php';

        return $this->success('', ['redirect_url' => $redirectUrl]);
    }

    private function isValidDownloadId(string $id): bool
    {
        return (bool) preg_match('/^[a-f0-9\-]{36}$/i', $id);
    }

    private function getZipUrl(string $downloadId): ?string
    {
        $response = $this->modx->runProcessor(GetFile::class, ['downloadId' => $downloadId]);
        if ($response->isError()) {
            return null;
        }
        $data = $response->getObject();
        return isset($data['zip']) && strpos($data['zip'], 'http') === 0 ? $data['zip'] : null;
    }

    private function downloadZip(string $zipUrl): ?string
    {
        $this->initApiClient();
        $request = $this->apiFactory->createRequest('GET', $zipUrl);
        try {
            $response = $this->apiClient->sendRequest($request);
        } catch (ClientExceptionInterface $e) {
            $this->modx->log(modX::LOG_LEVEL_ERROR, $e->getMessage());
            return null;
        }
        if ($response->getStatusCode() !== 200) {
            return null;
        }
        $filename = basename(parse_url($zipUrl, PHP_URL_PATH)) ?: 'modx-upgrade.zip';
        $zipPath = $this->tempDir . $filename;
        $body = $response->getBody();
        if (file_put_contents($zipPath, $body->getContents()) === false) {
            return null;
        }
        return $zipPath;
    }

    private function extractZip(string $zipPath): bool
    {
        if (is_dir($this->extractDir)) {
            $this->removeDirectory($this->extractDir);
        }
        if (!mkdir($this->extractDir, 0755, true) && !is_dir($this->extractDir)) {
            return false;
        }

        $forcePclZip = (bool) $this->modx->getOption('core_upgrade_force_pcl_zip', null, false);

        if (!$forcePclZip && class_exists(\ZipArchive::class)) {
            $zip = new \ZipArchive();
            if ($zip->open($zipPath) === true) {
                $result = $zip->extractTo($this->extractDir);
                $zip->close();
                return $result;
            }
        }

        if ($this->modx->getService('archive', 'compression.xPDOZip', XPDO_CORE_PATH, $zipPath)) {
            $result = $this->modx->archive->unpack($this->extractDir);
            return $result !== false;
        }

        return false;
    }

    private function findArchiveRoot(): ?string
    {
        $dirs = array_filter(glob($this->extractDir . '*', GLOB_ONLYDIR), 'is_dir');
        foreach ($dirs as $dir) {
            $corePath = $dir . DIRECTORY_SEPARATOR . 'core';
            $managerPath = $dir . DIRECTORY_SEPARATOR . 'manager';
            if (is_dir($corePath) && is_dir($managerPath)) {
                return rtrim($dir, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
            }
        }
        if (is_dir($this->extractDir . 'core') && is_dir($this->extractDir . 'manager')) {
            return $this->extractDir;
        }
        return null;
    }

    private function copyFiles(): bool
    {
        $basePath = rtrim($this->modx->getOption('base_path'), DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
        $corePath = rtrim($this->modx->getOption('core_path'), DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
        $managerPath = rtrim($this->modx->getOption('manager_path'), DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
        $connectorsPath = rtrim($this->modx->getOption('connectors_path'), DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
        $assetsPath = rtrim($this->modx->getOption('assets_path'), DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;

        $pairs = [
            $this->archiveRoot . 'setup' => $basePath . 'setup',
            $this->archiveRoot . 'core' => $corePath,
            $this->archiveRoot . 'manager' => $managerPath,
            $this->archiveRoot . 'connectors' => $connectorsPath,
        ];
        if (is_dir($this->archiveRoot . 'assets')) {
            $pairs[$this->archiveRoot . 'assets'] = $assetsPath;
        }

        $excludeFromCore = ['config' . DIRECTORY_SEPARATOR . 'config.inc.php'];

        foreach ($pairs as $source => $destination) {
            if (!is_dir($source)) {
                continue;
            }
            $exclude = [];
            if ($source === $this->archiveRoot . 'core') {
                $exclude = $excludeFromCore;
            }
            if (!$this->recurseCopy($source, $destination, $exclude)) {
                return false;
            }
        }

        $rootFiles = ['index.php', 'ht.access'];
        foreach ($rootFiles as $file) {
            $src = $this->archiveRoot . $file;
            $dst = $basePath . $file;
            if (is_file($src)) {
                if (!copy($src, $dst)) {
                    return false;
                }
            }
        }

        $sep = DIRECTORY_SEPARATOR;
        $setupConfigCore = $this->archiveRoot . 'setup' . $sep . 'includes' . $sep . 'config.core.php';
        if (is_file($setupConfigCore)) {
            $dstConfigCore = $basePath . 'setup' . $sep . 'includes' . $sep . 'config.core.php';
            if (!copy($setupConfigCore, $dstConfigCore)) {
                return false;
            }
        }

        return true;
    }

    private function isPathExcluded(string $relative, array $exclude): bool
    {
        foreach ($exclude as $ex) {
            $prefix = $ex . DIRECTORY_SEPARATOR;
            if ($relative === $ex || strpos($relative, $prefix) === 0) {
                return true;
            }
        }
        return false;
    }

    private function recurseCopy(string $source, string $destination, array $exclude = []): bool
    {
        if (!is_dir($destination) && !$this->ensureDirectory($destination)) {
            return false;
        }
        $source = rtrim($source, DIRECTORY_SEPARATOR);
        $len = strlen($source) + 1;
        $dir = new \RecursiveDirectoryIterator($source, \RecursiveDirectoryIterator::SKIP_DOTS);
        $iter = new \RecursiveIteratorIterator($dir, \RecursiveIteratorIterator::SELF_FIRST);
        foreach ($iter as $item) {
            $subPath = substr($item->getPathname(), $len);
            $relative = str_replace('/', DIRECTORY_SEPARATOR, $subPath);
            if ($this->isPathExcluded($relative, $exclude)) {
                continue;
            }
            $destPath = $destination . DIRECTORY_SEPARATOR . $relative;
            if ($item->isDir()) {
                if (!$this->ensureDirectory($destPath)) {
                    return false;
                }
            } elseif (!$this->copyItem($item->getPathname(), $destPath)) {
                return false;
            }
        }
        return true;
    }

    private function ensureDirectory(string $path): bool
    {
        return is_dir($path) || (mkdir($path, 0755, true) && is_dir($path));
    }

    private function copyItem(string $source, string $destPath): bool
    {
        $destDir = dirname($destPath);
        if (!$this->ensureDirectory($destDir)) {
            return false;
        }
        if (is_link($source)) {
            $target = readlink($source);
            if ($target !== false && !file_exists($destPath)) {
                @symlink($target, $destPath);
            }
            return true;
        }
        return copy($source, $destPath);
    }

    private function prepareSetup(): bool
    {
        $basePath = rtrim($this->modx->getOption('base_path'), DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
        $lockedFile = $basePath . 'setup' . DIRECTORY_SEPARATOR . '.locked';
        if (file_exists($lockedFile)) {
            @unlink($lockedFile);
        }
        return modSessionHandler::flushSessions($this->modx);
    }

    private function cleanup(): void
    {
        if ($this->tempDir && is_dir($this->tempDir)) {
            $this->removeDirectory($this->tempDir);
        }
    }

    private function removeDirectory(string $dir): void
    {
        $dir = rtrim($dir, DIRECTORY_SEPARATOR);
        if (!is_dir($dir)) {
            return;
        }
        $items = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($dir, \RecursiveDirectoryIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::CHILD_FIRST
        );
        foreach ($items as $item) {
            if ($item->isDir()) {
                rmdir($item->getPathname());
            } else {
                unlink($item->getPathname());
            }
        }
        rmdir($dir);
    }
}
