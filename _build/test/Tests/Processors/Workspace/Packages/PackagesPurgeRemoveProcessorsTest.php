<?php

/*
 * This file is part of the MODX Revolution package.
 *
 * Copyright (c) MODX, LLC
 *
 * For complete copyright and license information, see the COPYRIGHT and LICENSE
 * files found in the top-level directory of this distribution.
 *
 * @package modx-test
 */

namespace MODX\Revolution\Tests\Processors\Workspace\Packages;

use MODX\Revolution\Error\modError;
use MODX\Revolution\modWorkspace;
use MODX\Revolution\modX;
use MODX\Revolution\Processors\Workspace\Packages\Remove;
use MODX\Revolution\Tests\Processors\Workspace\Packages\Support\PurgeProcessorTestDouble;
use MODX\Revolution\Tests\Processors\Workspace\Packages\Support\RemoveProcessorTestDouble;
use MODX\Revolution\Transport\modTransportPackage;
use Yoast\PHPUnitPolyfills\TestCases\TestCase;

/**
 * Unit tests for {@see Purge} and {@see Remove} package processors (core removal flow).
 *
 * @package modx-test
 * @group Processors
 * @group Workspace
 * @group PackagesPurgeRemove
 */
class PackagesPurgeRemoveProcessorsTest extends TestCase
{
    /** @var list<string> */
    private array $tempRoots = [];

    protected function tearDown(): void
    {
        foreach ($this->tempRoots as $root) {
            $this->deleteDirectory($root);
        }
        $this->tempRoots = [];
        parent::tearDown();
    }

    private function deleteDirectory(string $path): void
    {
        if (!is_dir($path)) {
            return;
        }
        $path = rtrim($path, '/');
        foreach (glob($path . '/*') ?: [] as $item) {
            is_dir($item) ? $this->deleteDirectory($item) : @unlink($item);
        }
        @rmdir($path);
    }

    /**
     * Creates a workspace directory layout and a mocked package pointing at it.
     *
     * @return array{0: modTransportPackage, 1: string, 2: string} package, zip path, unpacked dir path
     */
    private function makeWorkspaceWithPackageFiles(
        bool $withZip,
        bool $withDir,
        string $zipFileName = 'demo-1.0.0-pl.transport.zip'
    ): array {
        $base = sys_get_temp_dir() . '/modx_pkg_ws_' . uniqid('', true);
        $workspacePath = $base . '/ws/';
        $packagesDir = $workspacePath . 'packages';
        mkdir($packagesDir, 0777, true);
        $zipPath = $packagesDir . '/' . $zipFileName;
        $dirPath = $packagesDir . '/' . basename($zipFileName, '.transport.zip') . '/';
        if ($withZip) {
            touch($zipPath);
        }
        if ($withDir) {
            mkdir($dirPath, 0777, true);
        }
        $this->tempRoots[] = $base;

        $workspace = $this->createMock(modWorkspace::class);
        $workspace->method('get')->with('path')->willReturn($workspacePath);

        $signature = basename($zipFileName, '.transport.zip');
        $package = $this->createMock(modTransportPackage::class);
        $package->method('getOne')->with('Workspace')->willReturn($workspace);
        $package->method('get')->willReturnCallback(static function ($key) use ($zipFileName, $signature) {
            if ($key === 'source') {
                return $zipFileName;
            }
            if ($key === 'signature') {
                return $signature;
            }

            return null;
        });
        $package->method('getPrimaryKey')->willReturn($signature);

        return [$package, $zipPath, $dirPath];
    }

    private function createModxShell(?modError $error = null): modX
    {
        $modx = $this->createMock(modX::class);
        $modx->method('lexicon')->willReturnCallback(static fn ($key) => $key);
        $modx->method('log')->willReturn(null);
        if ($error !== null) {
            $modx->error = $error;
        }

        return $modx;
    }

    // --- Remove::initialize ---

    public function testRemoveInitializeFailsWhenSignatureMissing(): void
    {
        $modx = $this->createModxShell($this->createMock(modError::class));
        $remove = new Remove($modx, ['signature' => '']);

        $this->assertSame('package_err_ns', $remove->initialize());
    }

    public function testRemoveInitializeFailsWhenPackageNotFound(): void
    {
        $modx = $this->createModxShell($this->createMock(modError::class));
        $modx->expects($this->once())
            ->method('getObject')
            ->with(modTransportPackage::class, 'missing')
            ->willReturn(null);
        $remove = new Remove($modx, ['signature' => 'missing']);

        $this->assertSame('package_err_nf', $remove->initialize());
    }

    public function testRemoveInitializeLoadsPackageAndNormalizesForceTrueString(): void
    {
        $modx = $this->createModxShell($this->createMock(modError::class));
        $pkg = $this->createMock(modTransportPackage::class);
        $modx->method('getObject')->willReturn($pkg);
        $remove = new Remove($modx, ['signature' => 'demo-1.0.0-pl', 'force' => 'true']);

        $this->assertTrue($remove->initialize());
        $this->assertSame($pkg, $remove->package);
        $this->assertTrue($remove->getProperty('force'));
    }

    // --- Remove::process ---

    public function testRemoveProcessReturnsFailureWhenBothRemovalsFailWithZipAndDirPresent(): void
    {
        [$pkg, ,] = $this->makeWorkspaceWithPackageFiles(true, true);
        $pkg->expects($this->once())->method('removePackage')->with(false)->willReturn(false);
        $pkg->expects($this->once())->method('remove')->willReturn(false);

        $error = $this->createMock(modError::class);
        $error->expects($this->once())->method('failure')->willReturn(['success' => false, 'failed' => true]);

        $modx = $this->createModxShell($error);
        $modx->expects($this->never())->method('invokeEvent');

        $remove = new RemoveProcessorTestDouble($modx, ['signature' => 'x', 'force' => false]);
        $remove->package = $pkg;

        $result = $remove->process();

        $this->assertIsArray($result);
        $this->assertFalse($result['success']);
        $this->assertSame([], $remove->filesystemOperations);
    }

    public function testRemoveProcessSucceedsWhenRemovePackageFailsButRemoveSucceeds(): void
    {
        [$pkg, $zipPath, $dirPath] = $this->makeWorkspaceWithPackageFiles(true, true);
        $pkg->expects($this->once())->method('removePackage')->with(false)->willReturn(false);
        $pkg->expects($this->once())->method('remove')->willReturn(true);

        $error = $this->createMock(modError::class);
        $error->method('success')->willReturn(['success' => true]);

        $modx = $this->createModxShell($error);
        $modx->expects($this->once())->method('invokeEvent')->with(
            'OnPackageRemove',
            $this->callback(static function (array $payload) use ($pkg) {
                return isset($payload['package']) && $payload['package'] === $pkg;
            })
        );

        $remove = new RemoveProcessorTestDouble($modx, ['signature' => 'x', 'force' => false]);
        $remove->package = $pkg;

        $result = $remove->process();

        $this->assertIsArray($result);
        $this->assertTrue($result['success']);
        $this->assertSame(
            [['zip', $zipPath], ['dir', $dirPath]],
            $remove->filesystemOperations
        );
    }

    public function testRemoveProcessSucceedsWhenZipAndDirMissingAndRemoveSucceeds(): void
    {
        [$pkg, $zipPath, $dirPath] = $this->makeWorkspaceWithPackageFiles(false, false);
        $pkg->expects($this->never())->method('removePackage');
        $pkg->expects($this->once())->method('remove')->willReturn(true);

        $error = $this->createMock(modError::class);
        $error->method('success')->willReturn(['success' => true]);

        $modx = $this->createModxShell($error);
        $modx->expects($this->once())->method('invokeEvent');

        $remove = new RemoveProcessorTestDouble($modx, ['signature' => 'x', 'force' => false]);
        $remove->package = $pkg;

        $remove->process();

        $this->assertSame(
            [['zip', $zipPath], ['dir', $dirPath]],
            $remove->filesystemOperations
        );
    }

    // --- Purge::removePackage ---

    public function testPurgeRemovePackageSkipsFilesystemWhenZipDirPresentAndBothDatabaseRemovalsFail(): void
    {
        [$pkg, ,] = $this->makeWorkspaceWithPackageFiles(true, true);
        $pkg->expects($this->once())->method('removePackage')->with(true)->willReturn(false);
        $pkg->expects($this->once())->method('remove')->willReturn(false);

        $modx = $this->createModxShell($this->createMock(modError::class));
        $modx->expects($this->never())->method('invokeEvent');

        $purge = new PurgeProcessorTestDouble($modx, []);
        $purge->removePackage($pkg);

        $this->assertSame([], $purge->filesystemOperations);
    }

    public function testPurgeRemovePackageRunsFilesystemCleanupWhenOnlyRemovePackageFailsThenRemoveSucceeds(): void
    {
        [$pkg, $zipPath, $dirPath] = $this->makeWorkspaceWithPackageFiles(true, true);
        $pkg->expects($this->once())->method('removePackage')->with(true)->willReturn(false);
        $pkg->expects($this->once())->method('remove')->willReturn(true);

        $modx = $this->createModxShell($this->createMock(modError::class));
        $modx->expects($this->once())->method('invokeEvent')->with(
            'OnPackageRemove',
            $this->callback(static function (array $payload) use ($pkg) {
                return isset($payload['package']) && $payload['package'] === $pkg;
            })
        );

        $purge = new PurgeProcessorTestDouble($modx, []);
        $purge->removePackage($pkg);

        $this->assertSame(
            [['zip', $zipPath], ['dir', $dirPath]],
            $purge->filesystemOperations
        );
    }

    public function testPurgeRemovePackageRunsFilesystemCleanupWhenZipAndDirMissing(): void
    {
        [$pkg, $zipPath, $dirPath] = $this->makeWorkspaceWithPackageFiles(false, false);
        $pkg->expects($this->never())->method('removePackage');
        $pkg->expects($this->once())->method('remove')->willReturn(true);

        $modx = $this->createModxShell($this->createMock(modError::class));
        $modx->expects($this->once())->method('invokeEvent');

        $purge = new PurgeProcessorTestDouble($modx, []);
        $purge->removePackage($pkg);

        $this->assertSame(
            [['zip', $zipPath], ['dir', $dirPath]],
            $purge->filesystemOperations
        );
    }
}
