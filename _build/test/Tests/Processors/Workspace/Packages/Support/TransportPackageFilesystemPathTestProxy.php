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
 * @internal
 */

namespace MODX\Revolution\Tests\Processors\Workspace\Packages\Support;

use MODX\Revolution\modX;
use MODX\Revolution\Processors\Workspace\Packages\TransportPackageFilesystemTrait;
use MODX\Revolution\Transport\modTransportPackage;

/**
 * @internal
 */
final class TransportPackageFilesystemPathTestProxy
{
    use TransportPackageFilesystemTrait;

    public function __construct(
        public modX $modx
    ) {
    }

    /**
     * @return array{transportZip: string, transportDir: string}
     */
    public function resolvePaths(modTransportPackage $package): array
    {
        return $this->resolveTransportPaths($package);
    }
}
