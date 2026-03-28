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

use MODX\Revolution\modWorkspace;
use MODX\Revolution\modX;
use MODX\Revolution\Tests\Processors\Workspace\Packages\Support\TransportPackageFilesystemPathTestProxy;
use MODX\Revolution\Transport\modTransportPackage;
use Yoast\PHPUnitPolyfills\TestCases\TestCase;

/**
 * Unit tests for TransportPackageFilesystemTrait::resolveTransportPaths().
 *
 * Uses mocked modX (no DB / harness) so they run in any environment with Composer dev deps.
 *
 * @package modx-test
 * @group Processors
 * @group Workspace
 * @group TransportPackageFilesystemTrait
 */
class TransportPackageFilesystemTraitTest extends TestCase
{
    private function mockModx(?string $corePathForFallback = null): modX
    {
        $modx = $this->createMock(modX::class);
        if ($corePathForFallback !== null) {
            $modx->method('getOption')->with('core_path', null, '')->willReturn($corePathForFallback);
        }

        return $modx;
    }

    /**
     * @param string|null $source value from package get('source'); null means omit from callback (simulate empty)
     * @param string      $signature package signature when source is empty
     */
    private function mockPackage(
        ?modWorkspace $workspace,
        ?string $source,
        string $signature = 'foo-1.0.0-pl'
    ): modTransportPackage {
        $package = $this->createMock(modTransportPackage::class);
        $package->method('getOne')->with('Workspace')->willReturn($workspace);
        $package->method('get')->willReturnCallback(function ($key) use ($source, $signature) {
            if ($key === 'source') {
                return $source;
            }
            if ($key === 'signature') {
                return $signature;
            }

            return null;
        });

        return $package;
    }

    private function mockWorkspace(string $path): modWorkspace
    {
        $workspace = $this->createMock(modWorkspace::class);
        $workspace->method('get')->with('path')->willReturn($path);

        return $workspace;
    }

    public function testResolvePathsUsesWorkspaceBaseAndNormalizesTrailingSlash(): void
    {
        $proxy = new TransportPackageFilesystemPathTestProxy($this->mockModx());
        $ws = $this->mockWorkspace('/var/modx/ws/');
        $pkg = $this->mockPackage($ws, 'bar-2.0.0-pl.transport.zip');

        $paths = $proxy->resolvePaths($pkg);

        $this->assertSame('/var/modx/ws/packages/bar-2.0.0-pl.transport.zip', $paths['transportZip']);
        $this->assertSame('/var/modx/ws/packages/bar-2.0.0-pl/', $paths['transportDir']);
    }

    public function testResolvePathsAddsPackagesSegmentWhenWorkspacePathHasNoTrailingSlash(): void
    {
        $proxy = new TransportPackageFilesystemPathTestProxy($this->mockModx());
        $ws = $this->mockWorkspace('/var/modx/ws');
        $pkg = $this->mockPackage($ws, 'baz-1.0.0-pl.transport.zip');

        $paths = $proxy->resolvePaths($pkg);

        $this->assertSame('/var/modx/ws/packages/baz-1.0.0-pl.transport.zip', $paths['transportZip']);
        $this->assertSame('/var/modx/ws/packages/baz-1.0.0-pl/', $paths['transportDir']);
    }

    public function testResolvePathsUsesBasenameWhenSourceContainsSubdirectory(): void
    {
        $proxy = new TransportPackageFilesystemPathTestProxy($this->mockModx());
        $ws = $this->mockWorkspace('/ws/');
        $pkg = $this->mockPackage($ws, 'nested/dir/pkg-1.0.0-pl.transport.zip');

        $paths = $proxy->resolvePaths($pkg);

        $this->assertSame('/ws/packages/pkg-1.0.0-pl.transport.zip', $paths['transportZip']);
        $this->assertSame('/ws/packages/pkg-1.0.0-pl/', $paths['transportDir']);
    }

    public function testResolvePathsDerivesZipNameFromSignatureWhenSourceEmpty(): void
    {
        $proxy = new TransportPackageFilesystemPathTestProxy($this->mockModx());
        $ws = $this->mockWorkspace('/ws/');
        $pkg = $this->mockPackage($ws, '', 'myext-3.1.0-pl');

        $paths = $proxy->resolvePaths($pkg);

        $this->assertSame('/ws/packages/myext-3.1.0-pl.transport.zip', $paths['transportZip']);
        $this->assertSame('/ws/packages/myext-3.1.0-pl/', $paths['transportDir']);
    }

    public function testResolvePathsFallsBackToCorePathWhenNoWorkspace(): void
    {
        $proxy = new TransportPackageFilesystemPathTestProxy($this->mockModx('/test/core'));
        $pkg = $this->mockPackage(null, 'orphan-1.0.0-pl.transport.zip');

        $paths = $proxy->resolvePaths($pkg);

        $this->assertSame('/test/core/packages/orphan-1.0.0-pl.transport.zip', $paths['transportZip']);
        $this->assertSame('/test/core/packages/orphan-1.0.0-pl/', $paths['transportDir']);
    }

    public function testResolvePathsCorePathFallbackNormalizesTrailingSlash(): void
    {
        $proxy = new TransportPackageFilesystemPathTestProxy($this->mockModx('/test/core/'));
        $pkg = $this->mockPackage(null, 'x-1.0.0-pl.transport.zip');

        $paths = $proxy->resolvePaths($pkg);

        $this->assertSame('/test/core/packages/x-1.0.0-pl.transport.zip', $paths['transportZip']);
    }

    public function testResolvePathsNullSourceUsesSignature(): void
    {
        $proxy = new TransportPackageFilesystemPathTestProxy($this->mockModx());
        $ws = $this->mockWorkspace('/ws/');
        $pkg = $this->mockPackage($ws, null, 'sig-1.0.0-pl');

        $paths = $proxy->resolvePaths($pkg);

        $this->assertSame('/ws/packages/sig-1.0.0-pl.transport.zip', $paths['transportZip']);
        $this->assertSame('/ws/packages/sig-1.0.0-pl/', $paths['transportDir']);
    }
}
