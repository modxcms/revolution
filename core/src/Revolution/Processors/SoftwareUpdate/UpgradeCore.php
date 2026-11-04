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
    private const MAX_ZIP_BYTES = 524288000; // 500 MiB

    public $permission = 'upgrade_core';
    public $languageTopics = ['setting'];

    /** @var string Configured temp base (not deleted wholesale). */
    private $tempBase = '';

    /** @var string Unique per-run work directory under tempBase. */
    private $workDir = '';

    /** @var string */
    private $extractDir = '';

    /** @var string */
    private $archiveRoot = '';

    /** @var resource|null */
    private $lockHandle;

    public function initialize()
    {
        if (function_exists('set_time_limit')) {
            @set_time_limit(0);
        }
        return parent::initialize();
    }

    public function checkPermissions()
    {
        if (!parent::checkPermissions()) {
            return false;
        }

        $allowedGroups = trim((string) $this->modx->getOption('core_upgrade_allowed_groups', null, ''));
        if ($allowedGroups === '') {
            return true;
        }

        $groups = array_filter(array_map('trim', explode(',', $allowedGroups)));
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

        $metadata = $this->getZipMetadata($downloadId);
        if ($metadata === null) {
            return $this->failure($this->modx->lexicon('software_update_err_retrieve'));
        }

        if (!$this->prepareWorkDirectory()) {
            return $this->failure($this->modx->lexicon('software_update_err_temp_dir'));
        }

        if (!$this->acquireLock()) {
            $this->cleanup();
            return $this->failure($this->modx->lexicon('software_update_err_busy'));
        }

        try {
            $zipPath = $this->downloadZip($metadata['zip']);
            if ($zipPath === null) {
                return $this->failure($this->modx->lexicon('software_update_err_download'));
            }

            if (!$this->verifyChecksum($zipPath, $metadata['sha256'])) {
                return $this->failure($this->modx->lexicon('software_update_err_checksum'));
            }

            if (!$this->extractZip($zipPath)) {
                return $this->failure($this->modx->lexicon('software_update_err_extract'));
            }

            $this->archiveRoot = $this->findArchiveRoot();
            if ($this->archiveRoot === null) {
                return $this->failure($this->modx->lexicon('software_update_err_archive_structure'));
            }

            if (!$this->copyFiles()) {
                return $this->failure($this->modx->lexicon('software_update_err_copy'));
            }

            if (!$this->prepareSetup()) {
                return $this->failure($this->modx->lexicon('software_update_err_prepare_setup'));
            }

            return $this->success('', ['redirect_url' => $this->buildSetupRedirectUrl()]);
        } finally {
            $this->cleanup();
            $this->releaseLock();
        }
    }

    private function isValidDownloadId(string $id): bool
    {
        return (bool) preg_match('/^[a-f0-9\-]{36}$/i', $id);
    }

    /**
     * @return array{zip: string, sha256: string}|null
     */
    private function getZipMetadata(string $downloadId): ?array
    {
        $response = $this->modx->runProcessor(GetFile::class, ['downloadId' => $downloadId]);
        if ($response->isError()) {
            return null;
        }
        $data = $response->getObject();
        if (
            empty($data['zip'])
            || !is_string($data['zip'])
            || strpos($data['zip'], 'https://') !== 0
            || empty($data['sha256'])
            || !is_string($data['sha256'])
            || !preg_match('/^[a-f0-9]{64}$/i', $data['sha256'])
        ) {
            return null;
        }

        return [
            'zip' => $data['zip'],
            'sha256' => strtolower($data['sha256']),
        ];
    }

    private function prepareWorkDirectory(): bool
    {
        $defaultTemp = $this->modx->getOption('core_path') . 'cache/upgrade/';
        $this->tempBase = rtrim(
            (string) $this->modx->getOption('core_upgrade_temp_dir', null, $defaultTemp),
            DIRECTORY_SEPARATOR
        ) . DIRECTORY_SEPARATOR;

        if (!is_dir($this->tempBase)) {
            $this->modx->getCacheManager();
            if (!$this->modx->cacheManager->writeTree($this->tempBase)) {
                return false;
            }
        }

        $this->workDir = $this->tempBase . 'run-' . bin2hex(random_bytes(8)) . DIRECTORY_SEPARATOR;
        if (!mkdir($this->workDir, 0700, true) && !is_dir($this->workDir)) {
            return false;
        }

        $this->extractDir = $this->workDir . 'extract' . DIRECTORY_SEPARATOR;
        return true;
    }

    private function acquireLock(): bool
    {
        $lockPath = $this->tempBase . 'upgrade.lock';
        $handle = @fopen($lockPath, 'c');
        if ($handle === false) {
            return false;
        }
        if (!flock($handle, LOCK_EX | LOCK_NB)) {
            fclose($handle);
            return false;
        }
        $this->lockHandle = $handle;
        return true;
    }

    private function releaseLock(): void
    {
        if ($this->lockHandle === null) {
            return;
        }
        flock($this->lockHandle, LOCK_UN);
        fclose($this->lockHandle);
        $this->lockHandle = null;
    }

    private function buildSetupRedirectUrl(): string
    {
        $siteUrl = rtrim((string) $this->modx->getOption('site_url', null, MODX_SITE_URL), '/') . '/';
        return $siteUrl . 'setup/index.php';
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

        $filename = basename((string) parse_url($zipUrl, PHP_URL_PATH)) ?: 'modx-upgrade.zip';
        $filename = str_replace(['/', '\\', "\0"], '', $filename);
        $zipPath = $this->workDir . $filename;
        $out = @fopen($zipPath, 'wb');
        if ($out === false) {
            return null;
        }

        $body = $response->getBody();
        $written = 0;
        while (!$body->eof()) {
            $chunk = $body->read(8192);
            if ($chunk === '') {
                break;
            }
            $written += strlen($chunk);
            if ($written > self::MAX_ZIP_BYTES) {
                fclose($out);
                @unlink($zipPath);
                return null;
            }
            if (fwrite($out, $chunk) === false) {
                fclose($out);
                @unlink($zipPath);
                return null;
            }
        }
        fclose($out);

        return $zipPath;
    }

    private function verifyChecksum(string $filePath, string $expectedSha256): bool
    {
        $actual = @hash_file('sha256', $filePath);
        return $actual !== false && hash_equals($expectedSha256, $actual);
    }

    private function extractZip(string $zipPath): bool
    {
        if (!class_exists(\ZipArchive::class)) {
            return false;
        }

        if (is_dir($this->extractDir)) {
            $this->removeDirectory($this->extractDir);
        }
        if (!mkdir($this->extractDir, 0700, true) && !is_dir($this->extractDir)) {
            return false;
        }

        $zip = new \ZipArchive();
        if ($zip->open($zipPath) !== true) {
            return false;
        }
        $result = $this->extractZipArchiveSafe($zip);
        $zip->close();

        return $result;
    }

    /**
     * Extracts ZipArchive entries with path traversal and symlink protection.
     */
    private function extractZipArchiveSafe(\ZipArchive $zip): bool
    {
        $baseDir = realpath($this->extractDir);
        if ($baseDir === false) {
            return false;
        }
        $prefix = $baseDir . DIRECTORY_SEPARATOR;

        for ($i = 0; $i < $zip->numFiles; $i++) {
            $name = $zip->getNameIndex($i);
            if ($name === false || $name === '' || strpos($name, "\0") !== false) {
                return false;
            }

            $normalized = str_replace('\\', '/', $name);
            if ($normalized[0] === '/' || preg_match('#(^|/)\.\.(?:/|$)#', $normalized)) {
                return false;
            }

            $isDir = substr($normalized, -1) === '/';
            $relative = str_replace('/', DIRECTORY_SEPARATOR, rtrim($normalized, '/'));
            $targetPath = $baseDir . DIRECTORY_SEPARATOR . $relative;

            $parent = dirname($targetPath);
            if (!is_dir($parent) && !mkdir($parent, 0700, true) && !is_dir($parent)) {
                return false;
            }
            $parentReal = realpath($parent);
            $outsideBase = $parentReal !== $baseDir
                && strpos($parentReal . DIRECTORY_SEPARATOR, $prefix) !== 0;
            if ($parentReal === false || $outsideBase) {
                return false;
            }

            $resolved = $parentReal . DIRECTORY_SEPARATOR . basename($targetPath);
            if ($resolved !== $baseDir && strpos($resolved, $prefix) !== 0) {
                return false;
            }

            if ($isDir) {
                if (!is_dir($resolved) && !mkdir($resolved, 0700, true)) {
                    return false;
                }
                continue;
            }

            $stat = $zip->statIndex($i);
            if (is_array($stat) && isset($stat['external_attr'])) {
                // Unix symlink: high byte of external attributes is file type 0120000
                $type = ($stat['external_attr'] >> 16) & 0170000;
                if ($type === 0120000) {
                    return false;
                }
            }

            $content = $zip->getFromIndex($i);
            if ($content === false || file_put_contents($resolved, $content) === false) {
                return false;
            }
        }

        return true;
    }

    private function findArchiveRoot(): ?string
    {
        $dirs = array_filter(glob($this->extractDir . '*', GLOB_ONLYDIR) ?: [], 'is_dir');
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
            $exclude = ($source === $this->archiveRoot . 'core') ? $excludeFromCore : [];
            if (!$this->recurseCopy($source, $destination, $exclude)) {
                return false;
            }
        }

        foreach (['index.php', 'ht.access'] as $file) {
            $src = $this->archiveRoot . $file;
            if (is_file($src) && !is_link($src) && !copy($src, $basePath . $file)) {
                return false;
            }
        }

        $sep = DIRECTORY_SEPARATOR;
        $setupConfigCore = $this->archiveRoot . 'setup' . $sep . 'includes' . $sep . 'config.core.php';
        if (is_file($setupConfigCore) && !is_link($setupConfigCore)) {
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
            if (strpos($relative, '..') !== false || $this->isPathExcluded($relative, $exclude)) {
                continue;
            }
            $destPath = $destination . DIRECTORY_SEPARATOR . $relative;
            if ($item->isLink()) {
                continue;
            }
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
        if (is_link($source)) {
            return true;
        }
        $destDir = dirname($destPath);
        if (!$this->ensureDirectory($destDir)) {
            return false;
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
        if ($this->workDir === '' || !is_dir($this->workDir)) {
            return;
        }

        $baseReal = realpath($this->tempBase);
        $workReal = realpath($this->workDir);
        if ($baseReal === false || $workReal === false) {
            return;
        }
        $prefix = $baseReal . DIRECTORY_SEPARATOR;
        if ($workReal === $baseReal || strpos($workReal . DIRECTORY_SEPARATOR, $prefix) !== 0) {
            $this->modx->log(
                modX::LOG_LEVEL_ERROR,
                'Refusing to clean unexpected upgrade work directory: ' . $this->workDir
            );
            return;
        }

        $this->removeDirectory($workReal);
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
                @rmdir($item->getPathname());
            } else {
                @unlink($item->getPathname());
            }
        }
        @rmdir($dir);
    }
}
